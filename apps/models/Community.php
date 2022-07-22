<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class Community extends \Phalcon\Mvc\Model
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
    public $type;

    /**
     *
     * @var string
     */
    public $cover;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $url;

    /**
     *
     * @var string
     */
    public $picture;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var string
     */
    public $date_create;

    /**
     *
     * @var string
     */
    public $editors;

    /**
     *
     * @var string
     */
    public $hashtag;

    /**
     *
     * @var boolean
     */
    public $banned;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("community");
        $this->belongsTo('users', 'App\Models\Users', 'id', ['alias' => 'Users']);
    }

    /**
     * Before save method for model.
     */
    public function beforeSave()
    {
        $this->picture = $this->Jsonb($this->picture);
        $this->editors = $this->JsonInt($this->Jsonb($this->editors));
        $this->hashtag = $this->Jsonb($this->hashtag);
    }

    /**
     * Before create method for model.
     */
    public function beforeCreate()
    {
        $this->picture = $this->Jsonb($this->picture);
        $this->editors = $this->JsonInt($this->Jsonb($this->editors));
        $this->hashtag = $this->Jsonb($this->hashtag);
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->picture = $this->Jsonb($this->picture);
        $this->editors = $this->JsonInt($this->Jsonb($this->editors));
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
