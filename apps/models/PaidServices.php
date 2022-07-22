<?php

namespace App\Models;

class PaidServices extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $types;

    /**
     *
     * @var integer
     */
    public $products;

    /**
     *
     * @var integer
     */
    public $users;

    /**
     *
     * @var string
     */
    public $service;

    /**
     *
     * @var integer
     */
    public $amount;

    /**
     *
     * @var string
     */
    public $payment_system;

    /**
     *
     * @var string
     */
    public $payment_id;

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
        $this->setSource("paid_services");
        $this->belongsTo('users', 'App\Models\Users', 'id', ['alias' => 'Users']);
    }

}
