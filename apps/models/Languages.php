<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class Languages extends \Phalcon\Mvc\Model
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
    public $name;

    /**
     *
     * @var string
     */
    public $code;

    /**
     *
     * @var string
     */
    public $dictionary;

    /**
     *
     * @var boolean
     */
    public $defaults;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("languages");
        $this->hasMany('code', 'App\Models\Currency', 'lang', ['alias' => 'Currency']);
    }

    /**
     * Before save method for model.
     */
    public function beforeSave()
    {
        $this->dictionary = $this->Jsonb($this->dictionary);
    }

    /**
     * Before create method for model.
     */
    public function beforeCreate()
    {
        $this->dictionary = $this->Jsonb($this->dictionary);
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->dictionary = $this->Jsonb($this->dictionary);
    }

}
