<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class Blogs extends \Phalcon\Mvc\Model
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
    public $content;

    /**
     *
     * @var string
     */
    public $picture;

    /**
     *
     * @var string
     */
    public $date_create;

    /**
     *
     * @var boolean
     */
    public $moderated;

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
        $this->setSource("blogs");
        $this->belongsTo('users', 'App\Models\Users', 'id', ['alias' => 'Users']);
    }

    /**
     * Before save method for model.
     */
    public function beforeSave()
    {
        $this->picture = $this->Jsonb($this->picture);
        $this->content = $this->Jsonb($this->content);
    }

    /**
     * Before create method for model.
     */
    public function beforeCreate()
    {
        $this->picture = $this->Jsonb($this->picture);
        $this->content = $this->Jsonb($this->content);
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->picture = $this->Jsonb($this->picture);
        $this->content = $this->Jsonb($this->content);
    }

    /**
     * Before update method for model.
     */
    public function beforeDelete()
    {
        /*if(!is_null($this->picture)) {
            $this->removeImages(json_decode($this->picture, true));
        }*/
        $dir_path = $this->users . '/blogs/' . $this->id;
        $this->removeUsersDir($dir_path);
    }


}
