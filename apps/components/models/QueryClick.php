<?php

class QueryClick extends \Helper
{
    private $db = null;
    private $sql;
    private $param = null;

    public function __construct()
    {
        $this->db = $this->getDI()->get('clickhouse');
    }

    public function sql(string $sql, ?array $param = null): QueryClick
    {
        $_t = clone $this;
        $_t->sql = $sql;
        $_t->param = $param;
        return $_t;
    }

    public function fetch()
    {
    }

    public function fetchAll()
    {
    }

    public function paginate()
    {
    }

    public function count()
    {}

    public function query()
    {}

}