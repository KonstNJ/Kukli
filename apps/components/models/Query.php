<?php
use Phalcon\Di;

class Query extends \Helper
{
    private $db = null;
    private $sql;
    private $param = null;

    public function __construct($db = 'db')
    {
        $this->db = $this->getDI()->get($db);
    }

    /**
     * @param string $sql
     * @param array|null $param
     * @return Query
     */
    public function sql(string $sql, ?array $param = null) : Query
    {
        $_t = clone $this;
        $_t->sql = $sql;
        $_t->param = $param;
        return $_t;
    }

    /**
     * @return mixed
     */
    public function fetch()
    {
        $items = $this->query();
        $items->setFetchMode(
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        return $items->fetch();
    }

    /**
     * @return mixed
     */
    public function fetchAll()
    {
        $items = $this->query();
        $items->setFetchMode(
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        return $items->fetchAll();
    }

    /**
     * @param int $current
     * @param int|null $limit
     * @return stdClass
     */
    public function paginate(int $current = 1, ?int $limit = 25)
    {
        $count = $this->count();
        $init = new \stdClass();
        $init->total_count = $count;
        $init->limit = $limit;
        $init->first = 1;
        $init->last = ($last = \floor($count / $limit)) > 0 ? $last : 1;
        $init->current = $init->last >= $current
            ? $current
            : $init->last;
        $init->previous = $init->current > 1
            ? $init->current - 1
            :  1;
        $init->next = $init->last > $init->current
            ? $init->current + 1
            : $init->current;
        $this->sql .= \sprintf(' limit %d', $limit);
        if($count > $limit)
        {
            $offset = ($init->current > 1)
                ? ($init->last > $init->current)
                    ? ($init->current * $limit) + 1
                    : $init->last * $limit
                : 1;
            $this->sql .= \sprintf('  offset %d', $offset);
        }
        if($init->total_count > 0)
        {
            $init->items = $this->fetchAll();
            return $init;
        }
        return [];
    }

    /**
     * @return mixed
     */
    private function count($set_cache = false)
    {
        $cache = Di::getDefault()->get('cache');
        $key = 'total_count-' . \md5($this->sql . \json_encode($this->param));
        if($cache->has($key))
        {
            $count = $cache->get($key);
        } else {
            $sql = sprintf('select count(*) from (%s) as t', $this->sql);
            $items = $this->query($sql);
            $items->setFetchMode(
                \Phalcon\Db\Enum::FETCH_ASSOC
            );
            $count = $items->fetch()['count'];
            if($set_cache)
            {
                $cache->set($key, (string) $count);
            }
        }
        return $count;
    }

    /**
     * @param string|null $sql
     * @return mixed
     */
    private function query(?string $sql = null)
    {
        return $this->db->query($sql ?? $this->sql, $this->param);
    }


}