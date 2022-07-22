<?php

namespace App\Content\Controllers;

use App\Validation\EmailValidation;
use Phalcon\Db\Enum;

class SubscribeController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function subscribeAction()
    {
        if($this->request->hasQuery('email'))
        {
            $messages = $this->validData(new EmailValidation(), $this->request->getQuery());
            if(count($messages))
            {
                $this->resultError($messages);
                return ;
            }
            $email = $this->request->getQuery('email', 'email');
            $sql = "insert into subscribe (users, email) values (:uid, :email) on conflict (users) do update set email=excluded.email";
            $query = $this->db->query($sql,
                [
                    'uid'=>$this->user,
                    'email'=>$email,
                ])->execute();
            if($query)
            {
                $this->getDI()
                    ->get('notifications')
                    ->subscribe($email);
                $this->resultOk();
                return ;
            }
        }
        $this->resultError();
    }

    public function unsubscribeAction()
    {
        if($this->request->hasQuery('email'))
        {
            $messages = $this->validData(new EmailValidation(), $this->request->getQuery());
            if(count($messages))
            {
                $this->resultError($messages);
                return ;
            }
            $sql = "delete from subscribe where email=:email returning email";
            $query = $this->db->query($sql, [
                'email' => $this->request->getQuery('email', 'email')
            ])->fetch(Enum::FETCH_ASSOC);
            if(!empty($query['email']))
            {
                $this->resultOk();
                return ;
            }
            $this->resultError('Not found', 404);
            return;
        }
        $this->resultError();
    }
}