<?php

namespace App\Frontend;

use Phalcon\Events\Manager;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Di\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Db\Adapter\Pdo\Postgresql;

class Module implements ModuleDefinitionInterface
{
    /**
     * @param DiInterface|null $di
     * @return void
     */
    public function registerAutoloaders(DiInterface $di = null)
    {
        $loader = new Loader();
	    $loader->registerNamespaces(
		    [
			    'App\Frontend\Controllers' => '../apps/modules/frontend/controllers/',
		    ]
	    );
        $loader->register();
    }

    /**
     * @param DiInterface $di
     * @return void
     */
    public function registerServices(DiInterface $di)
    {
        $di->set('dispatcher', function () use($di) {
            $eventManager = $di->getShared('eventsManager');
            $eventManager->attach('dispatch:beforeExecuteRoute', new \ReindexToken());
            $eventManager->attach('dispatch:beforeExecuteRoute', new \LangInit());
            $eventManager->attach('dispatch:beforeExecuteRoute', new \BodyParser());
            $eventManager->attach('dispatch:beforeException', new \ExceptionHandler());

            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('App\Frontend\Controllers\\');
            $dispatcher->setEventsManager($eventManager);
            return $dispatcher;
        });
        $di->set('view', function () {
            $view = new View();
            $view->setViewsDir('../apps/modules/frontend/views/');
            $view->disable();
            return $view;
        });
    }
}
