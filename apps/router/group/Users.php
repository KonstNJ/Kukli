<?php

namespace App\Router\Group;

use Phalcon\Mvc\Router\Group;

class Users extends Group
{
    public function initialize()
    {
        $this->setPaths([
            'module' => 'users',
            'namespace' => 'App\Users\Controllers'
        ]);
        $this->setPrefix('/users');
        $this->add('', [
            'controller' => 'index',
            'action'     => 'index'
        ]);
        $this->add('/forgot', [
            'controller' => 'forgot',
            'action' => 'init'
        ])->setName('forgot-confirm');
        $this->add('/confirm', [
            'controller' => 'confirm',
            'action' => 'init'
        ])->setName('confirm-link');
        $this->add('/(confirm|forgot)/([A-Za-z0-9]+)', [
            'controller' => 1,
            'action' => 'check',
            'key' => 2
        ]);
        $this->addPost('/forgot/([A-Za-z0-9]+)', [
            'controller' => 'forgot',
            'action' => 'confirm',
            'key' => 1
        ]);
        $this->add('/social', [
            'controller' => 'social',
            'action' => 'list'
        ]);
        $this->add('/signup', [
            'controller' => 'signup',
            'action' => 'index'
        ])->setName('users-signup');
        $this->add('/signup/([A-Za-z]+)', [
            'controller' => 'social',
            'action' => 'auth',
            'method' => 1
        ]);
        $this->add('/signup/([A-Za-z]+)/confirm', [
            'controller' => 'social',
            'action' => 'confirm',
            'method' => 1
        ]);
        $this->add('/signin', [
            'controller' => 'signin',
            'action' => 'auth'
        ]);
    }
}