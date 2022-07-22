<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class Condition extends \Phalcon\Mvc\Model
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("condition");
        $this->hasMany('id', 'App\Models\StatCounts', 'entity_id',
            [
                'alias' => 'StatCounts',
                'params' => [
                    'order' => 'counts desc',
                    'conditions' => 'entity=:entity:',
                    'bind' => [
                        'entity' => 'condition'
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
        $this->name = $this->Jsonb($this->name);
    }

    /**
     * Before create method for model.
     */
    public function beforeCreate()
    {
        $this->name = $this->Jsonb($this->name);
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->name = $this->Jsonb($this->name);
    }

}
