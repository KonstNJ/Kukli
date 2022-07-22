<?php

namespace App\Models;

class Notifications extends \Phalcon\Mvc\Model
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
    public $type;

    /**
     *
     * @var string
     */
    public $msg;

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
     *
     * @var boolean
     */
    public $read;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("notifications");
        $this->belongsTo('users', 'App\Models\Users', 'id', ['alias' => 'Users']);
    }

}
