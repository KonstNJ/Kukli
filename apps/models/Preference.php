<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class Preference extends \Phalcon\Mvc\Model
{
    use ExtendModel;

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
    public $interests;

    /**
     *
     * @var string
     */
    public $stats;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("preference");
        $this->belongsTo('users', 'App\Models\Users', 'id', ['alias' => 'Users']);
    }

    /**
     * Before save method for model.
     */
    public function beforeSave()
    {
        $this->interests = $this->Jsonb($this->interests);
        $this->stats = $this->Jsonb($this->stats);
    }

    /**
     * Before create method for model.
     */
    public function beforeCreate()
    {
        $this->interests = $this->Jsonb($this->interests);
        $this->stats = $this->Jsonb($this->stats);
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->interests = $this->Jsonb($this->interests);
        $this->stats = $this->Jsonb($this->stats);
    }

}
