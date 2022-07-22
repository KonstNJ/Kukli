<?php

namespace App\Models;

class Basket extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    public $guest;

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
    public $amount;

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
        $this->setSource("basket");
        $this->belongsTo('offers', 'App\Models\Offers', 'id', ['alias' => 'Offers']);
        $this->belongsTo('products', 'App\Models\Products', 'id', ['alias' => 'Products']);
        $this->belongsTo('users', 'App\Models\Users', 'id', ['alias' => 'Users']);
    }

}
