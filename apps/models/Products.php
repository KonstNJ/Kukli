<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;
use App\Models\Traits\ListsFind;

class Products extends \Phalcon\Mvc\Model
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
    public $product_code;

    /**
     *
     * @var integer
     */
    public $entity_type;

    /**
     *
     * @var string
     */
    public $category;

    /**
     *
     * @var integer
     */
    public $heroes;

    /**
     *
     * @var integer
     */
    public $producer;

    /**
     *
     * @var integer
     */
    public $brands;

    /**
     *
     * @var integer
     */
    public $brands_universe;

    /**
     *
     * @var integer
     */
    public $price;

    /**
     *
     * @var string
     */
    public $oldprice;

    /**
     *
     * @var integer
     */
    public $currency;

    /**
     *
     * @var string
     */
    public $hashtag;

    /**
     *
     * @var string
     */
    public $params;

    /**
     *
     * @var string
     */
    public $url;

    /**
     *
     * @var string
     */
    public $content;

    /**
     *
     * @var string
     */
    public $picture;

    /**
     *
     * @var string
     */
    public $media;

    /**
     *
     * @var string
     */
    public $payment;

    /**
     *
     * @var string
     */
    public $delivery;

    /**
     *
     * @var string
     */
    public $date_release;

    /**
     *
     * @var string
     */
    public $date_create;

    /**
     *
     * @var boolean
     */
    public $uptop;

    /**
     *
     * @var string
     */
    public $date_uptop;

    /**
     *
     * @var boolean
     */
    public $active;

    /**
     *
     * @var boolean
     */
    public $comments;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("products");
        $this->hasMany('id', 'App\Models\Basket', 'products', ['alias' => 'Basket']);
        $this->hasMany('id', 'App\Models\Ordering', 'products', ['alias' => 'Ordering']);
        $this->belongsTo('brands', 'App\Models\Brands', 'id', ['alias' => 'Brands']);
        $this->belongsTo('brands_universe', 'App\Models\BrandsUniverse', 'id', ['alias' => 'BrandsUniverse']);
        $this->belongsTo('category', 'App\Models\Category', 'treepath', ['alias' => 'Category']);
        $this->belongsTo('currency', 'App\Models\Currency', 'id', ['alias' => 'Currency']);
        $this->belongsTo('entity_type', 'App\Models\EntityType', 'id', ['alias' => 'EntityType']);
        $this->belongsTo('heroes', 'App\Models\Heroes', 'id', ['alias' => 'Heroes']);
        $this->belongsTo('producer', 'App\Models\Producer', 'id', ['alias' => 'Producer']);
    }

    /**
     * Before save method for model.
     */
    public function beforeSave()
    {
        $this->content = $this->parseLangsContent($this->content);
        $this->oldprice = $this->JsonInt($this->Jsonb($this->oldprice));
        $this->picture = $this->Jsonb($this->picture);
        $this->media = $this->Jsonb($this->media);
        $this->content = $this->Jsonb($this->content);
        $this->hashtag = $this->Jsonb($this->hashtag);
        $this->params = $this->Jsonb($this->params);
        $this->category = ($this->category > 0)
            ? $this->category
            : null;
        $this->heroes = ($this->heroes > 0)
            ? $this->heroes
            : null;
        $this->producer = ($this->producer > 0)
            ? $this->producer
            : null;
        $this->brands = ($this->brands > 0)
            ? $this->brands
            : null;
        $this->brands_universe = ($this->brands_universe > 0)
            ? $this->brands_universe
            : null;
    }

    /**
     * Before create method for model.
     */
    public function beforeCreate()
    {
        $this->content = $this->parseLangsContent($this->content);
        $this->oldprice = $this->JsonInt($this->Jsonb($this->oldprice));
        $this->picture = $this->Jsonb($this->picture);
        $this->media = $this->Jsonb($this->media);
        $this->content = $this->Jsonb($this->content);
        $this->hashtag = $this->Jsonb($this->hashtag);
        $this->params = $this->Jsonb($this->params);
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->content = $this->parseLangsContent($this->content);
        $this->oldprice = $this->JsonInt($this->Jsonb($this->oldprice));
        $this->picture = $this->Jsonb($this->picture);
        $this->media = $this->Jsonb($this->media);
        $this->content = $this->Jsonb($this->content);
        $this->hashtag = $this->Jsonb($this->hashtag);
        $this->params = $this->Jsonb($this->params);
    }

    /**
     * Before update method for model.
     */
    public function beforeDelete()
    {
        $this->removeDir($this->id);
        /*if(!is_null($this->picture)) {
            $this->removeImages(json_decode($this->picture, true));
        }*/
    }

}
