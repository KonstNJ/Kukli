<?php

use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;

class UsersAcl extends \Helper
{
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $role = $this->getDI()->getShared('auth')->getRole();
        if (!empty($role) && $role !== 'guest') {
            return true;
        } else {
            $message = [
                'status' => false,
                'msg' => 'Unauthorized'];
            $this->getDI()->get('resp')->getJson($message, 401);
            $event->stop();
            exit(0);
        }
    }
}