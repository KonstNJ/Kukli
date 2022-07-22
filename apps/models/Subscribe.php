<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class Subscribe extends \Phalcon\Mvc\Model
{
    use ExtendModel;

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $users;

    /**
     *
     * @var string
     */
    public $events;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var boolean
     */
    public $confirm;

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
        $this->setSource("subscribe");
        $this->belongsTo('users', 'App\Models\Users', 'id', ['alias' => 'Users']);
    }

    /**
     * Before save method for model.
     */
    public function beforeSave()
    {
        $this->events = $this->Jsonb($this->events);
    }

    /**
     * Before create method for model.
     */
    public function beforeCreate()
    {
        $this->events = $this->Jsonb($this->events);
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->events = $this->Jsonb($this->events);
    }

}
