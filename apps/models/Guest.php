<?php

namespace App\Models;

class Guest extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $ident;

    /**
     *
     * @var string
     */
    public $clientip;

    /**
     *
     * @var string
     */
    public $clientagent;

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
        $this->setSource("guest");
        $this->hasMany('id', 'App\Models\UsersAuth', 'guest', ['alias' => 'UsersAuth']);
    }

}
