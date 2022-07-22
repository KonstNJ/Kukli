<?php

namespace App\Models;

class Vote extends \Phalcon\Mvc\Model
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
    public $entity;

    /**
     *
     * @var integer
     */
    public $entity_id;

    /**
     *
     * @var integer
     */
    public $value;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("vote");
        $this->belongsTo('users', 'App\Models\Users', 'id', ['alias' => 'Users']);
    }

}
