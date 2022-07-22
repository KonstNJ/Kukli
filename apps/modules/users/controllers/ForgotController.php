<?php

namespace App\Users\Controllers;

use App\Models\Users;
use App\Validation\EmailValidation;
use App\Validation\PasswordConfirmValidation;
use App\Validation\SignUpValidation;
use phpDocumentor\Reflection\Utils;

class ForgotController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function initAction()
    {
        if($this->request->isPost())
        {
            $messages = $this->validData(new EmailValidation(), $this->request->getPost());
            if(count($messages))
            {
                $this->resultError($messages);
                return;
            }
            $item = Users::findFirst([
                'conditions' => 'email=:email:',
                'bind' => [
                    'email' => $this->request->getPost('email', 'email')
                ]
            ]);
            if($item)
            {
                if($item->banned === true)
                {
                    $this->resultError('Banned', 403);
                    return ;
                }
                $di = $this->getDI();
                $key_forgot = $di->getShared('string')::gen(35);
                $item->assign([
                    'forgot_password' => $key_forgot
                ]);
                if($item->update())
                {
                    $this->view->enable();
                    $this->getDI()
                        ->get('notifications')
                        ->forgot($item->email, $item->forgot_password);
                    $this->resultOk();
                    return ;
                }
                $this->resultError();
                return ;
            }
            $this->resultError('Not found', 404);
            return ;
        }
        $this->resultError();
    }

    public function checkAction(string $key)
    {
        $item = Users::findFirst([
            'conditions' => 'forgot_password=:key:',
            'bind' => [
                'key' => $this->filter->sanitize($key, ['string', 'striptags', 'trim'])
            ]
        ]);
        if ($item)
        {
            if($item->banned === true)
            {
                $this->resultError('Banned', 403);
                return ;
            }
            $this->resultOk();
            return ;
        }
        $this->resultError('Not found', 404);
    }

    public function confirmAction(string $key)
    {
        if($this->request->isPost())
        {
            $item = Users::findFirst([
                'conditions' => 'forgot_password=:key:',
                'bind' => [
                    'key' => $this->filter->sanitize($key, ['string', 'striptags', 'trim'])
                ]
            ]);
            if($item)
            {
                if($item->banned === true)
                {
                    $this->resultError('Banned', 403);
                    return ;
                }
                $messages = $this->validData(new PasswordConfirmValidation(), $this->request->getPost());
                if(count($messages))
                {
                    $this->resultError($messages);
                    return;
                }
                $item->assign([
                    'forgot_password' => null,
                    'password' => $this->security->hash($this->request->getPost('password'))
                ]);
                if($item->update())
                {
                    $users_data = $this->getDI()->getShared('auth')->saveDate($item);
                    $this->resultOk($users_data);
                    return ;
                }
                $this->resultError();
                return ;
            }
            $this->resultError('Not found', 404);
            return ;
        }
        $this->resultError();
    }
}