<?php

namespace App\Content;

use Phalcon\Events\Manager;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Di\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Db\Adapter\Pdo\Postgresql;

class Module implements ModuleDefinitionInterface
{
    public function registerAutoloaders(DiInterface $di = null)
    {
        $loader = new Loader();
	    $loader->registerNamespaces(
		    [
			    'App\Content\Controllers' => '../apps/modules/content/controllers/',
		    ]
	    );
        $loader->register();
    }

    public function registerServices(DiInterface $di)
    {
        $di->set('dispatcher', function () use($di) {
            $eventManager = $di->getShared('eventsManager');
            $eventManager->attach('dispatch:beforeExecuteRoute', new \ReindexToken());
            $eventManager->attach('dispatch:beforeExecuteRoute', new \LangInit());
            $eventManager->attach('dispatch:beforeExecuteRoute', new \BodyParser());
            $eventManager->attach('dispatch:beforeException', new \ExceptionHandler());

            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('App\Content\Controllers\\');
            $dispatcher->setEventsManager($eventManager);
            return $dispatcher;
        });
        $di->set('view', function () {
            $view = new View();
            $view->setViewsDir('../apps/modules/content/views/');
            $view->disable();
            return $view;
        });
    }
}
