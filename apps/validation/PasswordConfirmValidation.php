<?php

namespace App\Validation;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\StringLength;

class PasswordConfirmValidation extends Validation
{
    public function initialize()
    {
        $this->add(
            'password',
            new PresenceOf([
                'message' => ':field is required'
            ])
        );
        $this->add(
            'password',
            new StringLength([
                'min' => 6,
                'max' => 32,
                'messageMaximum' => "We don't like really long password",
                "messageMinimum"  => "We want more than just their initials",
                "includedMaximum" => true,
                "includedMinimum" => true,
            ])
        );
        $this->add(
            'password',
            new Confirmation([
                "message" => ":field doesn't match confirmation",
                "with"    => "confirm_password",
            ])
        );
    }
}