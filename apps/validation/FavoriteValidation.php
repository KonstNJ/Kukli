<?php

namespace App\Validation;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\Digit as DigitValidator;

class FavoriteValidation extends Validation
{
    public function initialize()
    {
        $this->add(
            'entity',
            new PresenceOf([
                'message' => ':field is required'
            ])
        );
        $this->add(
            "entity",
            new InclusionIn(
                [
                    "message" => ":field must be products or offers, or album, or blogs",
                    "domain"  => ["products", "offers", "album", "blogs"],
                ]
            )
        );
        $this->add(
            'entity_id',
            new PresenceOf([
                'message' => ':field is required'
            ])
        );
        $this->add(
            "entity_id",
            new DigitValidator(
                [
                    "message" => ":field must be numeric",
                ]
            )
        );
    }
}