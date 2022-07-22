<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class Pictures extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    public $album;

    /**
     *
     * @var string
     */
    public $picture;

    /**
     *
     * @var string
     */
    public $name;

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
     *
     * @var string
     */
    public $banned_msg;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("pictures");
        $this->belongsTo('users', 'App\Models\Users', 'id', ['alias' => 'Users']);
        $this->belongsTo('album', 'App\Models\Album', 'id', ['alias' => 'Album']);
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
