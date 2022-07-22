<?php

namespace App\Validation;

use Phalcon\Validation;
use Phalcon\Messages\Messages;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength\Min;

class ReviewsValidation extends Validation
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
            new Min(
                [
                    "min"     => 250,
                    "message" => ":field must be at least 250 characters long",
                    "included" => true
                ]
            )
        );
    }
}