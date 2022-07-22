<?php
namespace App\Api\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Collection;

abstract class ControllerBase extends Controller
{
    protected $user;
    protected $lang;

    public function initialize()
    {
        $this->user = $this->getDI()->getShared('auth')->getId();
        $this->lang = $this->persistent->has('lang')
            ? $this->persistent->get('lang')
            : 'en';
    }

    /**
     * @param \Phalcon\Validation $model
     * @param array $data
     * @return \Phalcon\Messages\Messages
     */
    protected function validData(\Phalcon\Validation $validation, array $data)
    {
        return $validation->validate($data);
    }

    /**
     * @param $data
     * @return array
     */
    protected function decodeJsonb($data) : array
    {
        if(is_null($data))
        {
            return [];
        }
        if(is_array($data))
        {
            return $data;
        }
        return \json_decode($data, true);
    }

    /**
     * @param array $param
     * @return array
     */
    protected function parseParam($param): array {
        $result = [];
        if(!empty($param) && is_array($param))
        {
            foreach (new \ArrayIterator($param) as $key => $val)
            {
                $result[$key] = $val;
            }
        }
        return $result;
    }

    /**
     * @return mixed|null
     */
    protected function getId()
    {
        if($this->request->hasPost('id'))
        {
            return $this->request->getPost('id', 'int');
        }
        return null;
    }

    /**
     * @return Collection
     */
    protected function getLimit() : Collection
    {
        $config = $this->getDI()->get('config')->paginator;
        $result = [
            'limit' => $this->request->getQuery('limit', 'int', $config->limit),
            'page' => $this->request->getQuery('page', 'int', 1),
        ];
        if($this->request->hasQuery('s'))
        {
            $result['s'] = $this->request->getQuery('s', 'name');
        }
        return (new Collection($result));
    }

    /**
     * @param $data
     * @param string $msg
     * @param int $code
     * @return void
     */
    protected function resultOk($data = [], string $msg = 'Ok', int $code = 200)
    {
        $this->getJson([
            'status' => true,
            'msg' => $msg,
            'result' => $data
        ], $code);
    }

    /**
     * @param $msg
     * @param int $code
     * @return void
     */
    protected function resultError($msg = 'Error', int $code = 400)
    {
        $this->getJson([
            'status' => false,
            'msg' => $msg
        ], $code);
    }

    /**
     * @param $data
     * @param int $code
     * @return mixed
     */
    private function getJson($data, int $code = 200)
    {
        $this->view->disable();
        return $this->getDI()
            ->getShared('resp')
            ->getJson($data, $code);
    }
}