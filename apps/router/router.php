<?php
use Phalcon\Mvc\Router;

$router = new Router(false);
$router->removeExtraSlashes(true);
$router->setDefaultModule('frontend');
$router->mount(new \App\Router\Group\Frontend());
$router->mount(new \App\Router\Group\Content());
$router->mount(new \App\Router\Group\Backend());
$router->mount(new \App\Router\Group\Users());
$router->mount(new \App\Router\Group\Api());
$router->notFound([
    'controller' => 'error',
    'action' => 'show404'
]);
return $router;
