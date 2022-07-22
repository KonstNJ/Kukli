<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class Album extends \Phalcon\Mvc\Model
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
    public $name;

    /**
     *
     * @var string
     */
    public $date_create;

    /**
     *
     * @var boolean
     */
    public $moderated;

    /**
     *
     * @var boolean
     */
    public $banned;

    /**
     *
     * @var string
     */
    public $banned_msg;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("album");
        $this->hasMany('id', 'App\Models\Pictures', 'album', ['alias' => 'Pictures']);
        $this->belongsTo('users', 'App\Models\Users', 'id', ['alias' => 'Users']);
    }

    /**
     * Before create method for model.
     */
    public function beforeCreate()
    {
        $this->name = $this->Jsonb($this->name);
    }

    /**
     * Before save method for model.
     */
    public function beforeSave()
    {
        $this->name = $this->Jsonb($this->name);
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->name = $this->Jsonb($this->name);
    }

    /**
     * Before delete method for model.
     */
    public function beforeDelete()
    {
        $dir_path = $this->users . '/album/' . $this->id;
        $this->removeUsersDir($dir_path);
    }

}
