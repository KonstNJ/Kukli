<?php

namespace App\Validation;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\Callback as CallbackValidator;

class ProductsValidation extends Validation
{
    public function initialize()
    {
        /*$this->add(
            'price',
            new PresenceOf([
                'message' => ':field is required'
            ])
        );
        $this->add(
            'price',
            new Numericality([
                'message' => ':field is not numeric',
            ])
        );*/
        $this->add(
            'content',
            new PresenceOf([
                'message' => ':field is required'
            ])
        );
        $this->add(
            'content',
            new CallbackValidator([
                'message' => 'The name field is required for all added languages',
                'callback' => function($data)
                {
                    if(!empty($data) && is_array($data))
                    {
                        $status = true;
                        foreach (new \ArrayIterator($data) as $content)
                        {
                            if(!empty($content['name']))
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