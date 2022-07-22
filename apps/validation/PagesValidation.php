<?php

namespace App\Validation;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\Callback as CallbackValidator;

class PagesValidation extends Validation
{
    public function initialize()
    {
        $this->add(
            'menu',
            new InclusionIn([
                'message' => ':field must be top or bottom, left or right, or footer',
                'domain' => ['top','bottom','left','right','footer']
            ])
        );
        $this->add(
            'content',
            new PresenceOf([
                'message' => ':field is required'
            ])
        );
        $this->add(
            'content',
            new CallbackValidator([
                'message' => ':field field is required for all added languages',
                'callback' => function($data) {
                    if(!empty($data['content']) && is_array($data['content']))
                    {
                        $status = true;
                        foreach (new \ArrayIterator($data['content']) as $content)
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