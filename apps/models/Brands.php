<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class Brands extends \Phalcon\Mvc\Model
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
    public $name;

    /**
     *
     * @var string
     */
    public $articul;

    /**
     *
     * @var string
     */
    public $date_release;

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
        $this->setSource("brands");
        $this->hasMany('id', 'App\Models\Offers', 'brands', ['alias' => 'Offers']);
        $this->hasMany('id', 'App\Models\Products', 'brands', ['alias' => 'Products']);
        $this->hasMany('id', 'App\Models\StatCounts', 'entity_id',
            [
                'alias' => 'StatCounts',
                'params' => [
                    'order' => 'counts desc',
                    'conditions' => 'entity=:entity:',
                    'bind' => [
                        'entity' => 'brands'
                    ]
                ]
            ]
        );
    }

    /**
     * Before save method for model.
     */
    public function beforeSave()
    {
        $this->picture = $this->Jsonb($this->picture);
        $this->name = $this->Jsonb($this->name);
    }

    /**
     * Before create method for model.
     */
    public function beforeCreate()
    {
        $this->picture = $this->Jsonb($this->picture);
        $this->name = $this->Jsonb($this->name);
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->picture = $this->Jsonb($this->picture);
        $this->name = $this->Jsonb($this->name);
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
