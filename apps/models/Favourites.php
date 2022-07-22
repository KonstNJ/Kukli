<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class Favourites extends \Phalcon\Mvc\Model
{
    use ExtendModel;

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $entity;

    /**
     * @var integer
     */
    public $entity_id;

    /**
     * @var integer
     */
    public $users;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("favourites");
    }

    /**
     * Before save method for model.
     */
    public function beforeSave()
    {
        $this->picture = $this->Jsonb($this->picture);
    }
}