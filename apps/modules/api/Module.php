<?php

namespace App\Api;

use Phalcon\Di\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\View;

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
			    'App\Api\Controllers' => '../apps/modules/api/controllers/',
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
            $eventManager->attach('dispatch:beforeExecuteRoute', new \UsersAcl());
            $eventManager->attach('dispatch:beforeExecuteRoute', new \BodyParser());
            $eventManager->attach('dispatch:beforeException', new \ExceptionHandler());

            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('App\Api\Controllers\\');
            $dispatcher->setEventsManager($eventManager);
            return $dispatcher;
        });
        $di->set('view', function () {
            $view = new View();
            $view->setViewsDir('../apps/modules/api/views/');
            $view->disable();
            return $view;
        });
    }
}
