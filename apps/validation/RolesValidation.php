<?php

namespace App\Validation;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Callback as CallbackValidator;
use Phalcon\Validation\Validator\Regex as RegexValidator;

class RolesValidation extends Validation
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
            'path',
            new RegexValidator([
                'pattern' => '/[a-zA-Z]+/',
                'message' => ':field must consist of their characters a-z'
            ])
        );
        $this->add(
            'path',
            new PresenceOf([
                'message' => ':field is required'
            ])
        );
        $this->add(
            'modules',
            new CallbackValidator([
                'message' => 'At least one module must be selected',
                'callback' => function($data) {
                    if(!empty($data['modules']))
                    {
                        return true;
                    }
                    return false;
                }
            ])
        );
    }
}