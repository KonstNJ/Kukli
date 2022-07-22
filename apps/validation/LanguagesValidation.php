<?php
namespace App\Validation;

use App\Models\Languages;
use Phalcon\Validation;
use Phalcon\Messages\Messages;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\Digit;

class LanguagesValidation extends Validation
{

    public function initialize()
    {
        $this->add(
            'name' ,
            new PresenceOf([
                'message' => 'The name is required'
            ])
        );
        $this->add(
            'name',
            new StringLength([
                'max' => 150,
                'min' => 2,
                'messageMaximum' => 'The name of the language should be no more than 150 characters',
                'messageMinimum' => 'The name of the language must be at least 2 characters',
            ])
        );
        $this->add(
            'code',
            new StringLength([
                'max' => 2,
                'min' => 2,
                'message' => 'The length of the language code must be equal to 2 characters',
            ])
        );
        /*$this->add(
            'code',
            new Uniqueness([
                'model' => new Languages(),
                'message' => ':field must be unique',
            ])
        );*/
    }

}