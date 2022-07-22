<?php
use Phalcon\Events\Event;
use Phalcon\Exception;
use Phalcon\Mvc\Dispatcher;

class BodyParser extends \Helper
{
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $contentType = $this->request->getHeader('CONTENT_TYPE');
        switch ($contentType) {
            case 'application/json':
            case 'application/json;charset=UTF-8':
                $jsonRawBody = $this->request->getJsonRawBody(true);

                if ($this->request->getRawBody() && !$jsonRawBody) {
                    $this->getDI()->get('resp')->getJson('Invalid JSON syntax', 400);
                    $event->stop();
                }
                $_POST = $jsonRawBody;
                break;
            default:
                if(!$this->request->isPost())
                {
                    parse_str($this->request->getRawBody(), $_POST);
                }
                break;
        }
    }
}