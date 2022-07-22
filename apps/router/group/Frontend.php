<?php
namespace App\Router\Group;

use Phalcon\Mvc\Router\Group;

class Frontend extends Group
{
    public function initialize()
    {
        $this->setPaths([
            'module' => 'frontend',
            'namespace' => 'App\Frontend\Controllers'
        ]);
        $this->setPrefix('');
        $this->add('/', [
            'controller' => 'index',
            'action' => 'index'
        ], ['GET','POST','PUT','DELETE']);
        $this->add('/error/401', [
            'controller' => 'error',
            'action' => 'show401'
        ]);
        $this->add('/error/404', [
            'controller' => 'error',
            'action' => 'show404'
        ]);

    }
}