<?php

namespace App\Tasks;

use Phalcon\Cli\Task;
use Phalcon\Db\Enum;
use Phalcon\Image\Adapter\Imagick;

class MainTask extends Task
{
    public function mainAction()
    {
        echo 'main';
    }
}