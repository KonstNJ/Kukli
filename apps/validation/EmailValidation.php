<?php

namespace App\Validation;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValidator;

class EmailValidation extends Validation
{
    public function initialize()
    {
        $this->add(
            'email',
            new PresenceOf([
                'message' => ':field is required'
            ])
        );
        $this->add(
            'email',
            new EmailValidator([
                'message' => ':field is not valid'
            ])
        );
    }
}