<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;

class CurrencyCourse extends \Phalcon\Mvc\Model
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
    public $currency;

    /**
     *
     * @var string
     */
    public $course;

    /**
     *
     * @var string
     */
    public $date_update;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("currency_course");
        $this->belongsTo('currency', 'App\Models\Currency', 'id', ['alias' => 'Currency']);
    }

    /**
     * Before save method for model.
     */
    public function beforeSave()
    {
        $this->course = $this->Jsonb($this->course);
    }

    /**
     * Before create method for model.
     */
    public function beforeCreate()
    {
        $this->course = $this->Jsonb($this->course);
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->course = $this->Jsonb($this->course);
    }

}
