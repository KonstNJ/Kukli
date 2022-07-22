<?php

namespace App\Models;

class UsersTape extends \Phalcon\Mvc\Model
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
    public $entity;

    /**
     *
     * @var integer
     */
    public $entity_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('db_users');
        $this->setSource("users_tape");
        $this->belongsTo('users', 'App\Models\Users', 'id', ['alias' => 'Users']);
    }

}
