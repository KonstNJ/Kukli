<?php
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;

class ExceptionHandler extends \Helper
{
    public function beforeException(Event $event, Dispatcher $dispatcher, \Exception $exception)
    {
        \error_log($exception->getMessage() . PHP_EOL . $exception->getTraceAsString());
        $this->getDI()->get('resp')->getJson('Error', 400);
        $event->stop();
    }
}