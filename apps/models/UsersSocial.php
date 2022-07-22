<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class UsersSocial extends \Phalcon\Mvc\Model
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
    public $social;

    /**
     *
     * @var integer
     */
    public $social_id;

    /**
     *
     * @var string
     */
    public $access_token;

    /**
     *
     * @var string
     */
    public $refresh_token;

    /**
     *
     * @var string
     */
    public $social_data;

    /**
     *
     * @var string
     */
    public $date_expires;

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
        $this->setSource("users_social");
        $this->belongsTo('users', 'App\Models\Users', 'id', ['alias' => 'Users']);
    }

    /**
     * Before save method for model.
     */
    public function beforeSave()
    {
        $this->social_data = $this->Jsonb($this->social_data);
    }

    /**
     * Before create method for model.
     */
    public function beforeCreate()
    {
        $this->social_data = $this->Jsonb($this->social_data);
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->social_data = $this->Jsonb($this->social_data);
    }

}
