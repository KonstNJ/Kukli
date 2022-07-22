<?php

namespace App\Validation;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Digit as DigitValidator;

class UseridValidation extends Validation
{
    public function initialize()
    {
        $this->add(
            'user',
            new PresenceOf([
                'message' => ':field is required'
            ])
        );
        $this->add(
            "user",
            new DigitValidator(
                [
                    "message" => ":field must be numeric",
                ]
            )
        );
    }
}