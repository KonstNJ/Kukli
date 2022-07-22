<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class News extends \Phalcon\Mvc\Model
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
    public $content;

    /**
     *
     * @var string
     */
    public $picture;

    /**
     *
     * @var boolean
     */
    public $active;

    /**
     *
     * @var string
     */
    public $date_create;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("news");
    }

    /**
     * Before save method for model.
     */
    public function beforeSave()
    {
        $this->content = $this->parseLangsContent($this->content);
        $this->picture = $this->Jsonb($this->picture);
        $this->content = $this->Jsonb($this->content);
    }

    /**
     * Before create method for model.
     */
    public function beforeCreate()
    {
        $this->content = $this->parseLangsContent($this->content);
        $this->picture = $this->Jsonb($this->picture);
        $this->content = $this->Jsonb($this->content);
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->content = $this->parseLangsContent($this->content);
        $this->picture = $this->Jsonb($this->picture);
        $this->content = $this->Jsonb($this->content);
    }

    /**
     * Before update method for model.
     */
    public function beforeDelete()
    {
        $this->removeDir('news', $this->id);
        /*if(!is_null($this->picture)) {
            $this->removeImages(json_decode($this->picture, true));
        }*/
    }

}
