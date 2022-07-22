<?php

namespace App\Models;

class UsersBanned extends \Phalcon\Mvc\Model
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
    public $users_to;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('db_users');
        $this->setSource("users_banned");
        $this->belongsTo('users', 'App\Models\Users', 'id', ['alias' => 'Users']);
        $this->belongsTo('users_to', 'App\Models\Users', 'id', ['alias' => 'Users']);
    }

}
