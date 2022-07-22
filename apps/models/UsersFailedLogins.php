<?php

namespace App\Models;

class UsersFailedLogins extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $agent;

    /**
     *
     * @var string
     */
    public $ip;

    /**
     *
     * @var integer
     */
    public $attempted;

    /**
     *
     * @var string
     */
    public $date_create;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('db_users');
        $this->setSource("users_failed_logins");
        $this->belongsTo('users', 'App\Models\Users', 'id', ['alias' => 'Users']);
    }

}
