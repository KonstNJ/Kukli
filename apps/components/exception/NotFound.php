<?php

use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Mvc\Dispatcher\Exception as DispatcherException;

class NotFound extends \Helper
{
    public function beforeException(Event $event, MvcDispatcher $dispatcher, Exception $exception)
    {
        if ($exception instanceof DispatcherException) {
            switch ($exception->getCode()) {
                case DispatcherException::EXCEPTION_HANDLER_NOT_FOUND:
                case DispatcherException::EXCEPTION_ACTION_NOT_FOUND:
                    $dispatcher->forward([
                        'controller' => 'error',
                        'action'     => 'route404',
                    ]);
                    return false;
            }
        }
        if ($dispatcher->getControllerName() !== 'error') {
            $dispatcher->forward([
                'controller' => 'error',
                'action'     => 'route500',
            ]);
        }
        return !$event->isStopped();
    }
}