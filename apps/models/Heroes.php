<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class Heroes extends \Phalcon\Mvc\Model
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
    public $hashtag;

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
        $this->setSource("heroes");
        $this->hasMany('id', 'App\Models\Products', 'heroes', ['alias' => 'Products']);
        $this->hasMany('id', 'App\Models\StatCounts', 'entity_id',
            [
                'alias' => 'StatCounts',
                'params' => [
                    'order' => 'counts desc',
                    'conditions' => 'entity=:entity:',
                    'bind' => [
                        'entity' => 'heroes'
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
        $this->hashtag = $this->Jsonb($this->hashtag);
    }

    /**
     * Before create method for model.
     */
    public function beforeCreate()
    {
        $this->picture = $this->Jsonb($this->picture);
        $this->name = $this->Jsonb($this->name);
        $this->hashtag = $this->Jsonb($this->hashtag);
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->picture = $this->Jsonb($this->picture);
        $this->name = $this->Jsonb($this->name);
        $this->hashtag = $this->Jsonb($this->hashtag);
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
