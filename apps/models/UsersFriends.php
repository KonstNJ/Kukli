<?php

namespace App\Models;

class UsersFriends extends \Phalcon\Mvc\Model
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
    public $users_from;

    /**
     *
     * @var integer
     */
    public $users_to;

    /**
     *
     * @var boolean
     */
    public $confirmed;

    /**
     *
     * @var string
     */
    public $date_crerate;

    /**
     *
     * @var string
     */
    public $date_confirmed;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('db_users');
        $this->setSource("users_friends");
        $this->belongsTo('users_from', 'App\Models\Users', 'id', ['alias' => 'Users']);
        $this->belongsTo('users_to', 'App\Models\Users', 'id', ['alias' => 'Users']);
    }

}
