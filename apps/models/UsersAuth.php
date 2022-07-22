<?php

namespace App\Models;

class UsersAuth extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $users;

    /**
     *
     * @var integer
     */
    public $guest;

    /**
     *
     * @var string
     */
    public $token;

    /**
     *
     * @var string
     */
    public $date_create;

    /**
     *
     * @var string
     */
    public $date_token_end;

    /**
     *
     * @var boolean
     */
    public $available;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('db_users');
        $this->setSource("users_auth");
        $this->belongsTo('guest', 'App\Models\Guest', 'id', ['alias' => 'Guest']);
        $this->belongsTo('users', 'App\Models\Users', 'id', ['alias' => 'Users']);
    }

}
