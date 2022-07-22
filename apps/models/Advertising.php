<?php

namespace App\Models;

class Advertising extends \Phalcon\Mvc\Model
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
    public $uid;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var string
     */
    public $url;

    /**
     *
     * @var string
     */
    public $media;

    /**
     *
     * @var string
     */
    public $date_crate;

    /**
     *
     * @var string
     */
    public $date_start;

    /**
     *
     * @var string
     */
    public $date_end;

    /**
     *
     * @var integer
     */
    public $impressions;

    /**
     *
     * @var integer
     */
    public $showing;

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
        $this->setSource("advertising");
    }

}
