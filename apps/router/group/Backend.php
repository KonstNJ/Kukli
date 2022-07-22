<?php
namespace App\Router\Group;

use Phalcon\Mvc\Router\Group;

class Backend extends Group
{
    public function initialize()
    {
        $this->setPaths([
            'module' => 'backend',
            'namespace' => 'App\Backend\Controllers'
        ]);
        $this->setPrefix('/admin');
        $this->add('', [
            'controller' => 'index',
            'action'     => 'index'
        ])->setName('admin-panel');
        $this->add('/:controller', [
            'controller' => 1,
            'action'     => 'view'
        ]);
        $this->add('/:controller/:action', [
            'controller' => 1,
            'action'     => 2,
        ]);
        $this->add('/:controller/:action/([0-9]+)', [
            'controller' => 1,
            'action'     => 2,
            'id'         => 3
        ]);
        $this->add('/:controller/(active|hiden|moderated|banned|unbanned)', [
            'controller' => 1,
            'action' => 'manipulation',
            'type' => 2
        ],['GET','POST']);
        $this->add('/:controller/(active|hiden|moderated|banned|unbanned)/([0-9]+)', [
            'controller' => 1,
            'action' => 'manipulation',
            'type' => 2,
            'id' => 3
        ],['GET','POST']);
        $this->add('/translate', [
            'controlle' => 'index',
            'action' => 'translate'
        ],['GET','POST']);
    }
}