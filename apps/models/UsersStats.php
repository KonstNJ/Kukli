<?php

namespace App\Models;

class UsersStats extends \Phalcon\Mvc\Model
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var integer
     */
    public $users;

    /**
     * @var integer
     */
    public $following;

    /**
     * @var integer
     */
    public $followers;

    /**
     * @var integer
     */
    public $reviews;

    /**
     * @var integer
     */
    public $blogs;

    /**
     * @var integer
     */
    public $sold;

    /**
     * @var integer
     */
    public $published;

    /**
     * @var integer
     */
    public $bought;

    public function initialize()
    {
        $this->setConnectionService('db_users');
        $this->setSource("users_stats");
        $this->belongsTo('users', 'App\Models\Users', 'id', ['alias' => 'Users']);
    }
}