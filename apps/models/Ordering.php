<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class Ordering extends \Phalcon\Mvc\Model
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
    public $orders;

    /**
     *
     * @var integer
     */
    public $products;

    /**
     *
     * @var integer
     */
    public $offers;

    /**
     *
     * @var integer
     */
    public $price;

    /**
     *
     * @var integer
     */
    public $amount;

    /**
     *
     * @var integer
     */
    public $currency;

    /**
     *
     * @var string
     */
    public $delivery;

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
        $this->setSource("ordering");
        $this->belongsTo('currency', 'App\Models\Currency', 'id', ['alias' => 'Currency']);
        $this->belongsTo('offers', 'App\Models\Offers', 'id', ['alias' => 'Offers']);
        $this->belongsTo('orders', 'App\Models\Orders', 'id', ['alias' => 'Orders']);
        $this->belongsTo('products', 'App\Models\Products', 'id', ['alias' => 'Products']);
    }

    /**
     * Before save method for model.
     */
    public function beforeSave()
    {
        $this->date_status = $this->Jsonb($this->date_status);
    }

    /**
     * Before create method for model.
     */
    public function beforeCreate()
    {
        $this->date_status = $this->Jsonb($this->date_status);
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->date_status = $this->Jsonb($this->date_status);
    }

}
