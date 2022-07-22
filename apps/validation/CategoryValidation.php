<?php

namespace App\Validation;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Callback as CallbackValidator;

class CategoryValidation extends Validation
{
    public function initialize()
    {
        $this->add(
            'name',
            new PresenceOf([
                'message' => ':field is required'
            ])
        );
        $this->add(
            'name',
            new CallbackValidator([
                'message' => ':field field is required for all added languages',
                'callback' => function($data)
                {
                    if(!empty($data['name']))
                    {
                        $status = true;
                        foreach (new \ArrayIterator($data['name']) as $name)
                        {
                            if(empty($name))
                            {
                                $status = false;
                            }
                        }
                        return $status;
                    }
                    return false;
                }
            ])
        );
    }
}