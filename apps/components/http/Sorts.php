<?php
use Phalcon\Collection;
use Phalcon\Di;

class Sorts extends \Helper
{
    private $query = null;
    private $sorts;
    private $filter;
    private $limit;
    private $default_sort;
    private $default_dir;

    /**
     * @param array $sorts
     * @param array $filter_type
     * @param string $default
     * @param string $dir_default
     */
    public function __construct(array $sorts = [], ?string $default = null, ?string $dir_default = null)
    {
        $di = $this->getDI();
        $this->sorts = $sorts;
        $this->limit = $di->get('config')->paginator->limit;
        $this->filter = $di->get('filter');
        $this->default_sort = $default;
        $this->default_dir = $dir_default;
    }

    /**
     * @param Collection $query
     * @return $this
     */
    public function query(Collection $query) : self
    {
        $_t = clone $this;
        $_t->query = $query;
        return $_t;
    }

    /**
     * @return array
     */
    public function get() : array
    {
        $result = [];
        $result['limit'] = $this->checkData('limit', 'int') ?: $this->limit;
        $result['current'] = $this->checkData('current', 'int') ?: 1;
        if(empty($this->sorts))
        {
            return  $result;
        }
        $sortdir = $this->checkData('sortdir', 'sortdir') ?: $this->default_dir;
        if($sort_key = $this->checkData('sort', 'name'))
        {
            $sort = $this->sorts[$sort_key] ?: $this->sorts[$this->default_sort];
        } else {
            $sort = $this->sorts[$this->default_sort];
        }
        $result['order'] = $sort .' '. $sortdir;
        if($search = $this->checkData('s', 'name'))
        {
            $result['s'] = $search;
        }
        if($show = $this->checkData('show', 'name'))
        {
            $result['show'] = $show;
        }
        return $result;
    }

    /**
     * @param string $key
     * @param $filter
     * @return false
     */
    private function checkData(string $key, $filter)
    {
        if($this->query->has($key))
        {
            return $this->filter->sanitize($this->query->get($key), $filter);
        }
        return false;
    }

}