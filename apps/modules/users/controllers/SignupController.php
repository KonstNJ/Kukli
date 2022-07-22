<?php

namespace App\Users\Controllers;

use App\Models\Users;
use App\Validation\PasswordConfirmValidation;
use App\Validation\SignUpValidation;

class SignupController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {
        if($this->request->isPost())
        {
            $messages = $this->validData(new PasswordConfirmValidation(), $this->request->getPost());
            if(count($messages))
            {
                $this->resultError($messages);
                return;
            }
            $messages = $this->validData(new SignUpValidation(), $this->request->getPost());
            if(count($messages))
            {
                $this->resultError($messages);
                return;
            }
            $item = new Users();
            if($item)
            {
                $item->assign([
                    'ident' => $this->getDI()->get('auth')->getId(),
                    'email' => $this->request->getPost('email', 'email'),
                    'password' => $this->security->hash($this->request->getPost('password')),
                    'name' => $this->request->getPost('name', 'name'),
                    'fullname' => $this->request->getPost('fullname', 'name'),
                    'patronymic' => $this->request->getPost('patronymic', 'name'),
                ]);
                if($item->create())
                {
                    $users_data = $this->getDI()->getShared('auth')->saveDate($item);
                    $this->resultOk($users_data);
                    return ;
                }
            }
        }
        $this->resultError();
    }
}