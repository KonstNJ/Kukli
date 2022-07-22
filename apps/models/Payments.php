<?php

namespace App\Models;

class Payments extends \Phalcon\Mvc\Model
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
    public $orders;

    /**
     *
     * @var string
     */
    public $ip;

    /**
     *
     * @var string
     */
    public $agent;

    /**
     *
     * @var integer
     */
    public $price;

    /**
     *
     * @var string
     */
    public $payer;

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
        $this->setSource("payments");
        $this->belongsTo('orders', 'App\Models\Orders', 'id', ['alias' => 'Orders']);
    }

}
