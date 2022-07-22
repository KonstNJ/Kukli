<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class Likes extends \Phalcon\Mvc\Model
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
    public $entity;

    /**
     *
     * @var integer
     */
    public $entity_id;

    /**
     *
     * @var integer
     */
    public $count_likes;

    /**
     *
     * @var integer
     */
    public $count_dislikes;

    /**
     *
     * @var string
     */
    public $likes_users;

    /**
     *
     * @var string
     */
    public $dislikes_users;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("likes");
    }

    /**
     * Before save method for model.
     */
    public function beforeSave()
    {
        $this->likes_users = $this->JsonInt($this->Jsonb($this->likes_users));
        $this->dislikes_users = $this->JsonInt($this->Jsonb($this->dislikes_users));
    }

    /**
     * Before create method for model.
     */
    public function beforeCreate()
    {
        $this->likes_users = $this->JsonInt($this->Jsonb($this->likes_users));
        $this->dislikes_users = $this->JsonInt($this->Jsonb($this->dislikes_users));
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->likes_users = $this->JsonInt($this->Jsonb($this->likes_users));
        $this->dislikes_users = $this->JsonInt($this->Jsonb($this->dislikes_users));
    }

}
