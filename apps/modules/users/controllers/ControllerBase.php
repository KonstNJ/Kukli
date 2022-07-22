<?php
namespace App\Users\Controllers;
use Phalcon\Mvc\Controller;

abstract class ControllerBase extends Controller
{
    public function initialize()
    {}

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
     * @param string|array $msg
     * @param int $code
     * @return void
     */
    public function resultOk($data = [], $msg = 'Ok', int $code = 200)
    {
        $this->getJson([
            'status' => true,
            'msg' => $msg,
            'result' => $data
        ], $code);
    }

    /**
     * @param string|array $msg
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