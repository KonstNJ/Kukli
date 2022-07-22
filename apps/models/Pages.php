<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class Pages extends \Phalcon\Mvc\Model
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
    public $positions;

    /**
     *
     * @var string
     */
    public $menu;

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
    public $modules;

    /**
     *
     * @var string
     */
    public $content;

    /**
     *
     * @var string
     */
    public $date_create;

    /**
     *
     * @var string
     */
    public $date_update;

    /**
     *
     * @var boolean
     */
    public $active;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("pages");
        $this->hasMany('id', 'App\Models\Pages', 'parent', ['alias' => 'Pages']);
        $this->belongsTo('parent', 'App\Models\Pages', 'id', ['alias' => 'Pages']);
    }

    /**
     * Before save method for model.
     */
    public function beforeSave()
    {
        $this->parent = ($this->parent > 0) ? $this->parent : null;
        $this->picture = $this->Jsonb($this->picture);
        $this->modules = $this->Jsonb($this->modules);
        $this->content = $this->Jsonb($this->content);
    }

    /**
     * Before create method for model.
     */
    public function beforeCreate()
    {
        $this->parent = ($this->parent > 0) ? $this->parent : null;
        $this->picture = $this->Jsonb($this->picture);
        $this->modules = $this->Jsonb($this->modules);
        $this->content = $this->Jsonb($this->content);
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->parent = ($this->parent > 0) ? $this->parent : null;
        $this->picture = $this->Jsonb($this->picture);
        $this->modules = $this->Jsonb($this->modules);
        $this->content = $this->Jsonb($this->content);
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
