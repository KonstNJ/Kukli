<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class Orders extends \Phalcon\Mvc\Model
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
    public $transaction_id;

    /**
     *
     * @var integer
     */
    public $users;

    /**
     *
     * @var string
     */
    public $products;

    /**
     *
     * @var string
     */
    public $delivery;

    /**
     *
     * @var integer
     */
    public $total_price;

    /**
     *
     * @var integer
     */
    public $total_amount;

    /**
     *
     * @var string
     */
    public $status;

    /**
     *
     * @var string
     */
    public $date_status;

    /**
     *
     * @var boolean
     */
    public $paid;

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
        $this->setSource("orders");
        $this->hasMany('id', 'App\Models\Ordering', 'orders', ['alias' => 'Ordering']);
        $this->hasMany('id', 'App\Models\Payments', 'orders', ['alias' => 'Payments']);
        $this->belongsTo('users', 'App\Models\Users', 'id', ['alias' => 'Users']);
    }

    /**
     * Before save method for model.
     */
    public function beforeSave()
    {
        $this->delivery = $this->Jsonb($this->delivery);
        $this->products = $this->Jsonb($this->products);
        $this->date_status = $this->Jsonb($this->date_status);
    }

    /**
     * Before create method for model.
     */
    public function beforeCreate()
    {
        $this->delivery = $this->Jsonb($this->delivery);
        $this->products = $this->Jsonb($this->products);
        $this->date_status = $this->Jsonb($this->date_status);
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->delivery = $this->Jsonb($this->delivery);
        $this->products = $this->Jsonb($this->products);
        $this->date_status = $this->Jsonb($this->date_status);
    }

}
