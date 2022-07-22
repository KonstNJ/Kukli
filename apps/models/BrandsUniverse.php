<?php

namespace App\Models;

class BrandsUniverse extends \Phalcon\Mvc\Model
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
        $this->setSource("brands_universe");
        $this->hasMany('id', 'App\Models\Products', 'brands_universe', ['alias' => 'Products']);
        $this->hasMany('id', 'App\Models\StatCounts', 'entity_id',
            [
                'alias' => 'StatCounts',
                'params' => [
                    'order' => 'counts desc',
                    'conditions' => 'entity=:entity:',
                    'bind' => [
                        'entity' => 'brands_universe'
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
