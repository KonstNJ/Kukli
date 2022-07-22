<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class Interests extends \Phalcon\Mvc\Model
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
    public $picture;

    /**
     *
     * @var integer
     */
    public $position;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("interests");
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
