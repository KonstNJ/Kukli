<?php

namespace App\Users\Controllers;

use App\Models\Users;
use App\Validation\SigninValidation;

class SigninController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function authAction()
    {
        /*$uid = $this->getDI()->getShared('auth')->getId();
        $this->resultOk(['users_id'=>$uid]);
        return ;*/
        if($this->request->isPost())
        {
            $messages = $this->validData(new SigninValidation(), $this->request->getPost());
            if(count($messages))
            {
                $this->resultError($messages);
                return ;
            }
            $item = Users::findFirst([
                'conditions' => 'email=:email:',
                'bind' => [
                    'email' => $this->request->getPost('email', 'email')
                ]
            ]);
            if(!$item)
            {
                $this->resultError('Not found', 404);
                return ;
            }
            if($item->banned===true)
            {
                $this->resultError('Banned', 403);
                return ;
            }
            if(!$this->security->checkHash($this->request->getPost('password'), $item->password))
            {
                $this->resultError('Incorrect data');
                return ;
            }
            $item->assign([
                'ident' => $this->getDI()->getShared('auth')->getId()
            ]);
            $item->update();
            $users_data = $this->getDI()->getShared('auth')->saveDate($item);
            $this->resultOk($users_data);
            return ;
        }
        $this->resultError();
    }


}