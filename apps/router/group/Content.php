<?php
namespace App\Router\Group;

use Phalcon\Mvc\Router\Group;

class Content extends Group
{
    public function initialize()
    {
        $this->setPaths([
            'module' => 'content',
            'namespace' => 'App\Content\Controllers'
        ]);
        $this->setPrefix('/get');
        $this->add('', [
            'controller' => 'index',
            'action' => 'index'
        ]);
        $this->add('/get/pages/([0-9]+)', [
            'controller' => 'index',
            'action' => 'index',
            'id' => 1
        ]);
        $this->add('/offers/code/hm_([0-9]+)_([0-9]+)', [
            'controller' => 'offers',
            'action' => 'getcode',
            'user' => 1,
            'id' => 2,
        ]);
        $this->add('/(subscribe|unsubscribe)', [
            'controller' => 'subscribe',
            'action' => 1
        ]);
        $this->add('/menu', [
            'controller' => 'menu',
            'action' => 'get'
        ]);
        $this->add('/breadcrumbs', [
            'controller' => 'menu',
            'action' => 'breadcrumbs'
        ]);
        $this->add('/country', [
            'controller' => 'country',
            'action' => 'get'
        ]);
        $this->add('/interests', [
            'controller' => 'interests',
            'action' => 'get'
        ]);
        $this->add('/filters', [
            'controller' => 'filters',
            'action' => 'search'
        ]);
        $this->add('/filters/params', [
            'controller' => 'filters',
            'action' => 'params'
        ]);
        $this->add('/langs', [
            'controller' => 'langs',
            'action' => 'lists'
        ]);
        $this->add('/langs/{lang:[a-z]{2}}', [
            'controller' => 'langs',
            'action' => 'set'
        ]);
        $this->add('/(products|offers|blogs|albums|shops|users)', [
            'controller' => 1,
            'action' => 'lists'
        ]);
        $this->add('/(products|offers|blogs|albums|shops|users)/([0-9]+)', [
            'controller' => 1,
            'action' => 'get',
            'id' => 2
        ]);
    }
}