<?php

namespace App\Validation;

use Phalcon\Validation;
use Phalcon\Messages\Messages;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

class ShopsValidation extends Validation
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
            new StringLength(
                [
                    "max"             => 255,
                    "min"             => 2,
                    "messageMaximum"  => "We don't like really long names",
                    "messageMinimum"  => "We want more than just their initials",
                    "includedMaximum" => true,
                    "includedMinimum" => true,
                ]
            )
        );
    }
}