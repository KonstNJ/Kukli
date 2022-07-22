<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class Roles extends \Phalcon\Mvc\Model
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
    public $path;

    /**
     *
     * @var string
     */
    public $modules;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('db_users');
        $this->setSource("roles");
        $this->hasMany('id', 'App\Models\Users', 'roles', ['alias' => 'Users']);
    }

    /**
     * Before save method for model.
     */
    public function beforeSave()
    {
        $this->modules = $this->Jsonb($this->modules);
    }

    /**
     * Before create method for model.
     */
    public function beforeCreate()
    {
        $this->modules = $this->Jsonb($this->modules);
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->modules = $this->Jsonb($this->modules);
    }

}
