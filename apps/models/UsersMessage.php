<?php

namespace App\Models;

class UsersMessage extends \Phalcon\Mvc\Model
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
    public $users_to;

    /**
     *
     * @var integer
     */
    public $users_from;

    /**
     *
     * @var integer
     */
    public $reply;

    /**
     *
     * @var string
     */
    public $message;

    /**
     *
     * @var string
     */
    public $picture;

    /**
     *
     * @var string
     */
    public $date_create;

    /**
     *
     * @var string
     */
    public $date_read;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('db_users');
        $this->setSource("users_message");
        $this->hasMany('id', 'App\Models\UsersMessage', 'reply', ['alias' => 'UsersMessage']);
        $this->belongsTo('reply', 'App\Models\UsersMessage', 'id', ['alias' => 'UsersMessage']);
        $this->belongsTo('users_from', 'App\Models\Users', 'id', ['alias' => 'Users']);
        $this->belongsTo('users_to', 'App\Models\Users', 'id', ['alias' => 'Users']);
    }

}
