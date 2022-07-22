<?php

namespace App\Models;

class BannedAnnotations extends \Phalcon\Mvc\Model
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
    public $intem;

    /**
     *
     * @var integer
     */
    public $item_id;

    /**
     *
     * @var integer
     */
    public $manage;

    /**
     *
     * @var integer
     */
    public $users;

    /**
     *
     * @var string
     */
    public $petition;

    /**
     *
     * @var string
     */
    public $date_crate;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("banned_annotations");
        $this->belongsTo('manage', 'App\Models\Users', 'id', ['alias' => 'Users']);
        $this->belongsTo('users', 'App\Models\Users', 'id', ['alias' => 'Users']);
    }

}
