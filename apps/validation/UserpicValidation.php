<?php

namespace App\Validation;

use Phalcon\Validation;
use Phalcon\Validation\Validator\File as FileValidator;

class UserpicValidation extends Validation
{
    public function initialize()
    {
        $this->add(
            'picture',
            new FileValidator([
                "maxSize"              => "2M",
                "messageSize"          => ":field exceeds the max file size (:size)",
                "allowedTypes"         => [
                    "image/jpeg",
                    "image/png",
                    "image/gif",
                    "image/bmp",
                ],
                "messageType"          => "Allowed file types are :types",
                "maxResolution"        => "900x700",
                "messageMaxResolution" => "Max resolution of :field is :resolution",
            ])
        );
    }
}