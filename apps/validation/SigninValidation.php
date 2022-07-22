<?php

namespace App\Validation;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\StringLength;

class SigninValidation extends Validation
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
    }
}