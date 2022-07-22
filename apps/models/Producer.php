<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class Producer extends \Phalcon\Mvc\Model
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
    public $description;

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
        $this->setSource("producer");
        $this->hasMany('id', 'App\Models\Products', 'producer', ['alias' => 'Products']);
        $this->hasMany('id', 'App\Models\StatCounts', 'entity_id',
            [
                'alias' => 'StatCounts',
                'params' => [
                    'order' => 'counts desc',
                    'conditions' => 'entity=:entity:',
                    'bind' => [
                        'entity' => 'producer'
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
        $this->description = $this->Jsonb($this->description);
    }

    /**
     * Before create method for model.
     */
    public function beforeCreate()
    {
        $this->picture = $this->Jsonb($this->picture);
        $this->description = $this->Jsonb($this->description);
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->picture = $this->Jsonb($this->picture);
        $this->description = $this->Jsonb($this->description);
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
