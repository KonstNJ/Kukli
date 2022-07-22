<?php

namespace App\Api\Controllers;

class ErrorController extends ControllerBase
{
    public function show404Action()
    {
        $this->resultError('Not Found', 404);
    }

    public function show401Action()
    {
        $this->resultError('Unauthorized', 401);
    }
}