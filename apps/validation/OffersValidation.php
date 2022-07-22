<?php

namespace App\Validation;

use Phalcon\Validation;
use Phalcon\Messages\Messages;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\StringLength\Min;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\InclusionIn;

class OffersValidation extends Validation
{
    public function initialize()
    {
        $this->add(
            'types',
            new InclusionIn(
                [
                    "message" => ":field must be buy or sell",
                    "domain"  => ["buy", "sell"],
                ]
            )
        );
        $this->add(
            'title',
            new PresenceOf([
                'message' => ':field is required'
            ])
        );
        $this->add(
            'title',
            new StringLength(
                [
                    "max"             => 250,
                    "min"             => 2,
                    "messageMaximum"  => "We don't like really long title",
                    "messageMinimum"  => "We want more than just their initials",
                    "includedMaximum" => true,
                    "includedMinimum" => true,
                ]
            )
        );
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
                    "min"     => 150,
                    "message" => ":field must be at least 250 characters long",
                    "included" => true
                ]
            )
        );
        $this->add(
            'price',
            new PresenceOf([
                'message' => ':field is required'
            ])
        );
        $this->add(
            'price',
            new Numericality(
                [
                    "message" => ":field is not numeric",
                ]
            )
        );
    }
}