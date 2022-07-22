<?php
use Phalcon\Events\Event;
use Phalcon\Exception;
use Phalcon\Mvc\Dispatcher;
class ReindexToken extends \Helper
{
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $this->getDI()->getShared('token')->valid();
        return true;
    }
}