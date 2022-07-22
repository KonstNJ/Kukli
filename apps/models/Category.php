<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class Category extends \Phalcon\Mvc\Model
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
    public $parent;

    /**
     *
     * @var string
     */
    public $treepath;

    /**
     *
     * @var integer
     */
    public $position;

    /**
     *
     * @var string
     */
    public $url;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $picture;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("category");
        $this->hasMany('id', 'App\Models\Category', 'parent', ['alias' => 'Category']);
        $this->hasMany('treepath', 'App\Models\Offers', 'category', ['alias' => 'Offers']);
        $this->hasMany('treepath', 'App\Models\Products', 'category', ['alias' => 'Products']);
        $this->hasMany('id', 'App\Models\StatCounts', 'entity_id',
            [
                'alias' => 'StatCounts',
                'params' => [
                    'order' => 'counts desc',
                    'conditions' => 'entity=:entity:',
                    'bind' => [
                        'entity' => 'category'
                    ]
                ]
            ]
        );
        $this->belongsTo('parent', 'App\Models\Category', 'id', ['alias' => 'Category']);
    }

    /**
     * Before save method for model.
     */
    public function beforeSave()
    {
        $this->parent = ($this->parent > 0)
            ? $this->parent
            : null;
        $this->name = $this->Jsonb($this->name);
    }

    /**
     * Before create method for model.
     */
    public function beforeCreate()
    {
        $this->parent = ($this->parent > 0)
            ? $this->parent
            : null;
        $this->name = $this->Jsonb($this->name);
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->parent = ($this->parent > 0)
            ? $this->parent
            : null;
        $this->name = $this->Jsonb($this->name);
    }

}
