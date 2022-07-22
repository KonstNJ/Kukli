<?php

namespace App\Users\Controllers;

use App\Models\Users;
use App\Models\UsersSocial;

class SocialController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function listAction()
    {
        $this->resultOk((new \Social())->getAutUrl());
    }

    public function authAction(string $method)
    {
        if(!$this->session->has('test_uuid'))
        {
            $this->session->set('test_uuid', $this->getDI()->getShared('auth')->getId());
        }
        $social = (new \Social())->init($method);
        if(!$social)
        {
            $this->resultError();
            return ;
        }
        $userProfile = $social->getUserProfile();
        $token_data = $social->getAccessToken();
        $uuid = $this->getDI()->getShared('auth')->getId();
        if($uuid === false)
        {
            $uuid = $this->getDI()->get('string')::uuid();
        }
        $UsersSocial = UsersSocial::findFirst([
            'conditions' => 'social=:social: and social_id=:social_id:',
            'bind' => [
                'social' => $method,
                'social_id' => $userProfile->identifier
            ]
        ]);
        if($UsersSocial)
        {
            $users = $UsersSocial->getUsers();
            $users->assign([
                'ident' => $uuid
            ]);
            $users->update();
            $users_data = $this->getDI()->getShared('auth')->saveDate($users);
            $this->resultOk($users_data);
            return ;
        } else {
            $email = $userProfile->email;
            if(!\is_null($email))
            {
                $users = Users::findFirst([
                    'conditions' => 'email=:email:',
                    'bind' => [
                        'email' => $email,
                    ]
                ]);
                if($users)
                {
                    if ($users->banned === true)
                    {
                        $this->resultError('Banned', 403);
                        return ;
                    }
                    if(!$users->getUsersSocial()->count())
                    {
                        $UsersSocial = new UsersSocial();
                        $UsersSocial->assign([
                            'social' => $method,
                            'social_id' => $userProfile->identifier,
                            'access_token' => $token_data['access_token'],
                            'refresh_token' => $token_data['refresh_token'] ?? '',
                            'social_data' => $userProfile,
                            'date_expires' => (new \DateTimeImmutable())->setTimestamp($token_data['expires_at'])->format('Y-m-d H:i:s.u')
                        ]);
                        $users->UsersSocial = $UsersSocial;
                        $users->update();
                    }

                    $users_data = $this->getDI()->getShared('auth')->saveDate($users);
                    $this->resultOk($users_data);
                    return ;
                } else {
                    $users = new Users();
                    $users->assign([
                        'ident' => $uuid,
                        'name' => $userProfile->firstName,
                        'fullname' => $userProfile->lastName,
                        'email' => $email,
                        'phone' => $userProfile->phone,
                        'gender' => $userProfile->gender,
                    ]);
                    if(!\is_null($userProfile->birthYear))
                    {
                        $users->date_of_birth = (new \DateTimeImmutable())->setDate($userProfile->birthYear, $userProfile->birthMonth, $userProfile->birthDay)->format('Y-m-d');
                    }
                    $users->UsersSocial = (new UsersSocial())->assign([
                        'social' => $method,
                        'social_id' => $userProfile->identifier,
                        'access_token' => $token_data['access_token'],
                        'refresh_token' => $token_data['refresh_token'] ?? '',
                        'social_data' => $userProfile,
                        'date_expires' => (new \DateTimeImmutable())->setTimestamp($token_data['expires_at'])->format('Y-m-d H:i:s.u')
                    ]);
                    if($users->save())
                    {
                        if(!\is_null($userProfile->photoURL))
                        {
                            if($picture = $this->getDI()->getShared('images')->social($userProfile->photoURL, $users->id))
                            {
                                $users->picture = $picture;
                                $users->update();
                            }
                        }
                        $users_data = $this->getDI()->getShared('auth')->saveDate($users);
                        $this->resultOk($users_data);
                        return ;
                    }
                }
            }
        }
        $this->resultError();
    }
}