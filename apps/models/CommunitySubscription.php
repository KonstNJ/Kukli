<?php

namespace App\Models;

class CommunitySubscription extends \Phalcon\Mvc\Model
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
    public $community;

    /**
     *
     * @var integer
     */
    public $users;

    /**
     *
     * @var string
     */
    public $date_create;

    /**
     *
     * @var boolean
     */
    public $banned;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("community_subscription");
        $this->belongsTo('users', 'App\Models\Users', 'id', ['alias' => 'Users']);
    }

}
