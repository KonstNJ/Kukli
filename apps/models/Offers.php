<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class Offers extends \Phalcon\Mvc\Model
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
    public $users;

    /**
     *
     * @var string
     */
    public $product_code;

    /**
     *
     * @var string
     */
    public $condition;

    /**
     *
     * @var string
     */
    public $category;

    /**
     *
     * @var integer
     */
    public $brands;

    /**
     *
     * @var string
     */
    public $types;

    /**
     *
     * @var string
     */
    public $params;

    /**
     *
     * @var string
     */
    public $content;

    /**
     *
     * @var integer
     */
    public $price;

    /**
     *
     * @var string
     */
    public $price_old;

    /**
     *
     * @var string
     */
    public $tags;

    /**
     *
     * @var string
     */
    public $date_end;

    /**
     *
     * @var string
     */
    public $picture;

    /**
     *
     * @var string
     */
    public $videos;

    /**
     *
     * @var integer
     */
    public $delivery;

    /**
     *
     * @var boolean
     */
    public $pick_up_point;

    /**
     *
     * @var boolean
     */
    public $active;

    /**
     *
     * @var boolean
     */
    public $publish;

    /**
     *
     * @var string
     */
    public $date_release;

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
     * @var string
     */
    public $date_create;

    /**
     *
     * @var boolean
     */
    public $moderated;

    /**
     *
     * @var boolean
     */
    public $flood;

    /**
     *
     * @var boolean
     */
    public $banned;

    /**
     *
     * @var string
     */
    public $banned_msg;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("offers");
        $this->hasMany('id', 'App\Models\Basket', 'offers', ['alias' => 'Basket']);
        $this->hasMany('id', 'App\Models\Ordering', 'offers', ['alias' => 'Ordering']);
        $this->belongsTo('brands', 'App\Models\Brands', 'id', ['alias' => 'Brands']);
        $this->belongsTo('category', 'App\Models\Category', 'treepath', ['alias' => 'Category']);
        $this->belongsTo('users', 'App\Models\Users', 'id', ['alias' => 'Users']);
    }

    /**
     * Before save method for model.
     */
    public function beforeSave()
    {
        $this->condition = $this->JsonInt($this->Jsonb($this->condition));
        $this->price_old = $this->JsonInt($this->Jsonb($this->price_old));
        $this->content = $this->Jsonb($this->content);
        $this->picture = $this->Jsonb($this->picture);
        $this->params = $this->Jsonb($this->params);
        $this->tags = $this->Jsonb($this->tags);
        $this->videos = $this->Jsonb($this->videos);
    }

    /**
     * Before create method for model.
     */
    public function beforeCreate()
    {
        $this->condition = $this->JsonInt($this->Jsonb($this->condition));
        $this->price_old = $this->JsonInt($this->Jsonb($this->price_old));
        $this->picture = $this->Jsonb($this->picture);
        $this->tags = $this->Jsonb($this->tags);
        $this->videos = $this->Jsonb($this->videos);
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->condition = $this->JsonInt($this->Jsonb($this->condition));
        $this->price_old = $this->JsonInt($this->Jsonb($this->price_old));
        $this->picture = $this->Jsonb($this->picture);
        $this->tags = $this->Jsonb($this->tags);
        $this->videos = $this->Jsonb($this->videos);
    }

    /**
     * Before update method for model.
     */
    public function beforeDelete()
    {
        if(!is_null($this->picture)) {
            $this->removeImages(json_decode($this->picture, true));
        }
    }

}
