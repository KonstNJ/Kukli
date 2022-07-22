<?php

namespace App\Validation;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Callback as CallbackValidator;

class ProducerValidation extends Validation
{
    public function initialize()
    {
        $this->add(
            'description',
            new PresenceOf([
                'message' => ':field is required'
            ])
        );
        $this->add(
            'description',
            new CallbackValidator([
                'message' => ':field field is required for all added languages',
                'callback' => function($data)
                {
                    if(!empty($data['description']))
                    {
                        $status = true;
                        foreach (new \ArrayIterator($data['description']) as $name)
                        {
                            if(!empty($name))
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