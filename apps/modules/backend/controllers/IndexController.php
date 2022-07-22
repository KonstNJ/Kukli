<?php

namespace App\Backend\Controllers;

use App\Validation\LanguagesValidation;
use App\Validation\TestValidation;


use Phalcon\Validation;
use Phalcon\Messages\Messages;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Callback as CallbackValidator;


class IndexController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {

    }

    public function viewAction()
    {

    }

    public function translateAction()
    {
        $this->view->disable();
        if($this->request->isPost())
        {
            $langs = [];
            $str = $this->request->getPost('lang_name', ['trim', 'string']);
            $translate = $this->getDI()->getShared('translate');
            foreach (new \ArrayIterator($this->request->getPost('lang')) as $lang)
            {
                $trans_str = $translate->init('en', $lang, $str);
                if($trans_str!=='')
                {
                    $langs[$lang] = $trans_str;
                }
            }
            $this->resultOk($langs);
            return ;
        }
        $this->resultError();
        return ;
    }
}