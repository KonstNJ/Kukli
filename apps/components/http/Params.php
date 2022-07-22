<?php
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Collection;
use Phalcon\Di;

class Params extends \Helper
{
    public function get(bool $header = false, array $param = []) : Collection
    {
        $request = $this->getDI()->get('request');
        $query = $request->getQuery();
        unset($query['_url']);
        if(!empty($param))
        {
            $query = array_merge($query, $param);
        }
        if(true == $header)
        {
            $query = array_merge($query, $request->getHeaders());
        }
        return $this->getCollection($query);
    }

    private function getCollection(?array $data = []): Collection
    {
        return (new Collection($data));
    }

}