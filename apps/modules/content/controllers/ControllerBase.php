<?php
namespace App\Content\Controllers;
use Phalcon\Collection;
use Phalcon\Mvc\Controller;

abstract class ControllerBase extends Controller
{
    /**
     * @var string
     * Uuid
     */
    protected $user;

    /**
     * @var string
     */
    protected $lang;

    /**
     * @return void
     */
    public function initialize()
    {
        $user = $this->getDI()->getShared('auth')->getId();
        $this->user = \is_numeric($user)
            ? $user
            : '0';
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
     * @return array
     */
    protected function decodeJsonb($data) : array
    {
        return (!\is_null($data)) ? \json_decode($data, true) : [];
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
     * @param string $msg
     * @param int $code
     * @return void
     */
    public function resultError($msg = 'Error', int $code = 400)
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