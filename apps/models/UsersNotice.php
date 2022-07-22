<?php

namespace App\Models;

class UsersNotice extends \Phalcon\Mvc\Model
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
    public $subscription;

    /**
     *
     * @var string
     */
    public $message;

    /**
     *
     * @var boolean
     */
    public $viewed;

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
        $this->setSource("users_notice");
        $this->belongsTo('subscription', 'App\Models\UsersSubscription', 'id', ['alias' => 'UsersSubscription']);
        $this->belongsTo('users', 'App\Models\Users', 'id', ['alias' => 'Users']);
    }

}
