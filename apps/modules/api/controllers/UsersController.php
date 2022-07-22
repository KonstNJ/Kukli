<?php

namespace App\Api\Controllers;

use App\Models\Users;
use App\Validation\PasswordConfirmValidation;

class UsersController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function listsAction()
    {
        $item = Users::findFirst([
            'conditions' => 'id=:user:',
            'bind' => [
                'user' => $this->user
            ]
        ]);
        if(!$item)
        {
            $this->resultError('Not found', 404);
            return;
        }
        if($this->request->isPost())
        {
            if($this->request->hasPost('email'))
            {
                $item->email = $this->request->getPost('email', 'email');
            }
            if($this->request->hasPost('status'))
            {
                $item->status = $this->request->getPost('status', 'string');
            }
            if($this->request->hasPost('url'))
            {
                $item->status = $this->request->getPost('url', 'name');
            }
            if($this->request->hasPost('phone'))
            {
                $item->phone = $this->request->getPost('phone', 'phone');
            }
            if($this->request->hasPost('name'))
            {
                $item->name = $this->request->getPost('name', 'name');
            }
            if($this->request->hasPost('fullname'))
            {
                $item->fullname = $this->request->getPost('fullname', 'name');
            }
            if($this->request->hasPost('patronymic'))
            {
                $item->patronymic = $this->request->getPost('patronymic', 'name');
            }
            if($this->request->hasPost('gender'))
            {
                $item->gender = $this->request->getPost('gender', 'name');
            }
            if($this->request->hasPost('country'))
            {
                $item->country = $this->request->getPost('country', 'name');
            }
            if($this->request->hasPost('date_of_birth'))
            {
                $item->date_of_birth = $this->request->getPost('date_of_birth', 'date');
            }
            if($this->request->hasPost('currency'))
            {
                $item->currency = $this->request->getPost('currency', 'code');
            }
            if($this->request->hasPost('tags'))
            {
                $item->interests = $this->request->getPost('tags', 'name');
            }
            if($this->request->hasFiles())
            {
                $images = $this->getDI()->getShared('images');
                $picture = $images->add($this->request, 'users');

                if(!empty($picture))
                {
                    if(!is_null($item->picture))
                    {
                        $images->imagesDelete(json_decode($item->picture, true));
                    }
                    $item->picture = $picture;
                }
            }
            if(!$item->update())
            {
                $this->resultError();
                return;
            }
        }
        $this->resultOk([
            'status' => $item->status,
            'url' => $item->url,
            'gender' => $item->gender,
            'email' => $item->email,
            'phone' => $item->phone,
            'name' => $item->name,
            'fullname' => $item->fullname,
            'patronymic' => $item->patronymic,
            'about_me' => $item->about_me,
            'date_of_birth' => $item->date_of_birth,
            'currency' => $item->currency,
            'country' => $item->country,
            'picture' => $this->decodeJsonb($item->picture)[0] ?? null,
            'tags' => $this->decodeJsonb($item->interests),
        ]);
    }

    public function passwordAction()
    {
        if($this->request->isPost())
        {
            $messages = $this->validData(new PasswordConfirmValidation(), $this->request->getPost());
            if(count($messages))
            {
                $this->resultError($messages);
                return ;
            }
            $item = Users::findFirst([
                'conditions' => 'id=:user:',
                'bind' => [
                    'user' => $this->user
                ]
            ]);
            if(!$item)
            {
                $this->resultError('Not found', 404);
                return ;
            }
            if($item->banned===true)
            {
                $this->resultError('Users is banned', 401);
                return ;
            }
            $item->assign([
                'password' => $this->security->hash($this->request->getPost('password')),
            ]);
            if($item->update())
            {
                $this->resultOk();
                return ;
            }
        }
        $this->resultError();
    }
}