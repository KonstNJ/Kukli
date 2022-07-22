<?php

namespace App\Validation;

use Phalcon\Validation;
use Phalcon\Messages\Messages;
use Phalcon\Validation\Validator\Callback as CallbackValidator;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

class NewsValidation extends Validation
{
    public function initialize()
    {
        $this->add(
            'content',
            new PresenceOf([
                'message' => ':field is required'
            ])
        );
        $this->add(
            'content',
            new CallbackValidator([
                'message' => 'The news must be described in at least one language',
                'callback' => function($data) {
                    if(!empty($data['content']) && is_array($data['content']))
                    {
                        $status = false;
                        foreach (new \ArrayIterator($data['content']) as $content)
                        {
                            if(!empty($content['name']) && !empty($content['content']))
                            {
                                $status = true;
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