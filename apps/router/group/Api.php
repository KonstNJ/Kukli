<?php
namespace App\Router\Group;

use Phalcon\Mvc\Router\Group;

class Api extends Group
{
    public function initialize()
    {
        $this->setPaths([
            'module' => 'api',
            'namespace' => 'App\Api\Controllers'
        ]);
        $this->setPrefix('/api');
        $this->add('', [
            'controller' => 'index',
            'action'     => 'index'
        ])->setName('cabinet-panel');
        $this->add('/:controller', [
            'controller' => 1,
            'action' => 'lists'
        ]);
        $this->add('/:controller/:action', [
            'controller' => 1,
            'action' => 2
        ]);
        $this->add('/:controller/([0-9]+)', [
            'controller' => 1,
            'action' => 'get'
        ]);
        $this->add('/:controller/:action', [
            'controller' => 'blogs',
            'action' => 'lists'
        ]);
        $this->add('/users/(following|followers|recommendations)', [
            'controller' => 'follow',
            'action' => 1
        ]);
        $this->add('/users/follow/(add|delete)', [
            'controller' => 'follow',
            'action' => 'add'
        ]);

    }
}