<?php

namespace App\Models;

class EntityVote extends \Phalcon\Mvc\Model
{

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
    public $value;

    /**
     *
     * @var integer
     */
    public $likes;

    /**
     *
     * @var integer
     */
    public $comments;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("entity_vote");
    }

}
