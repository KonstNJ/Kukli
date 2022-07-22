<?php

namespace App\Validation;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\StringLength;

class SignUpValidation extends Validation
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
            'name',
            new PresenceOf([
                'message' => ':field is required'
            ])
        );
        $this->add(
            'name',
            new StringLength([
                'min' => 2,
                'max' => 100,
                'messageMaximum' => "We don't like really long names",
                "messageMinimum"  => "We want more than just their initials",
                "includedMaximum" => true,
                "includedMinimum" => true,
            ])
        );
    }
}