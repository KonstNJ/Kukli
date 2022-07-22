<?php

use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Di;

class LangInit extends \Helper
{
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $lang = $this->request->hasHeader('lang')
            ? $this->request->getHeader('lang')
            : 'en';
        $this->persistent->set('lang', $lang);
        return true;
    }
}