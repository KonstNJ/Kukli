<?php

namespace App\Backend;

use Phalcon\Events\Manager;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Di\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Escaper;
use Phalcon\Flash\Direct;


class Module implements ModuleDefinitionInterface
{
    public function registerAutoloaders(DiInterface $di = null)
    {
        $loader = new Loader();
	    $loader->registerNamespaces(
		    [
			    'App\Backend\Controllers' => '../apps/modules/backend/controllers/',
		    ]
	    );
        $loader->register();
    }

    public function registerServices(DiInterface $di)
    {
        $di->set('dispatcher', function () use($di) {
            $dispatcher = new Dispatcher();
            $eventManager = new Manager();
            $dispatcher->setEventsManager($eventManager);
            $eventManager->attach('dispatch:beforeExecuteRoute', new \ReindexToken());
            $eventManager->attach('dispatch:beforeExecuteRoute', new \LangInit());
            $eventManager->attach('dispatch:beforeExecuteRoute', new \BackendAcl());
            $eventManager->attach('dispatch:beforeExecuteRoute', new \BodyParser());
            #$eventManager->attach('dispatch:beforeException', new \ExceptionHandler());
            $dispatcher->setDefaultNamespace('App\Backend\Controllers\\');
            return $dispatcher;
        });
        $di->set('flashSession', function () {
            $escaper = new Escaper();
            $flash = new Direct($escaper);
            $flash->setImplicitFlush(false);
            $flash->setCssClasses([
                'error'   => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice'  => 'alert alert-info',
                'warning' => 'alert alert-warning'
            ]);
            return $flash;
        });
        $di->set('view', function () {
            $view = new View();
            $view->setViewsDir('../apps/modules/backend/views/');
            return $view;
        });
    }
}
