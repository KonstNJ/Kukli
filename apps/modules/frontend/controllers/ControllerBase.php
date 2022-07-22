<?php
namespace App\Frontend\Controllers;
use Phalcon\Mvc\Controller;

abstract class ControllerBase extends Controller
{
    /**
     * @var lang code
     */
    protected $lang;

    /**
     * @return void
     */
    public function initialize()
    {
        $this->lang = $this->persistent->has('lang')
            ? $this->persistent->get('lang')
            : 'en';
    }

    /**
     * @param $data
     * @param string $msg
     * @param int $code
     * @return void
     */
    public function resultOk($data = [], string $msg = 'Ok', int $code = 200)
    {
        $this->getJson([
            'status' => true,
            'msg' => $msg,
            'result' => $data
        ], $code);
    }

    /**
     * @param string $msg
     * @return void
     */
    public function resultError(string $msg = 'Error')
    {
        $this->getJson([
            'status' => false,
            'msg' => $msg
        ], 400);
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