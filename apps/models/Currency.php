<?php

namespace App\Models;

class Currency extends \Phalcon\Mvc\Model
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
    public $lang;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $code;

    /**
     *
     * @var string
     */
    public $symbol;

    /**
     *
     * @var string
     */
    public $isocode;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("currency");
        $this->hasMany('id', 'App\Models\CurrencyCourse', 'currency', ['alias' => 'CurrencyCourse']);
        $this->hasMany('id', 'App\Models\Ordering', 'currency', ['alias' => 'Ordering']);
        $this->hasMany('id', 'App\Models\Products', 'currency', ['alias' => 'Products']);
        $this->belongsTo('lang', 'App\Models\Languages', 'code', ['alias' => 'Languages']);
    }

}
