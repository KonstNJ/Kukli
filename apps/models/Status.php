<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class Status extends \Phalcon\Mvc\Model
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
    public $name;

    /**
     *
     * @var string
     */
    public $local_name;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('db_users');
        $this->setSource("status");
        $this->hasMany('id', 'App\Models\Users', 'status', ['alias' => 'Users']);
    }

    /**
     * Before save method for model.
     */
    public function beforeSave()
    {
        $this->local_name = $this->Jsonb($this->local_name);
    }

    /**
     * Before create method for model.
     */
    public function beforeCreate()
    {
        $this->local_name = $this->Jsonb($this->local_name);
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->local_name = $this->Jsonb($this->local_name);
    }

}
