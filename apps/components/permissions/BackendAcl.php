<?php


use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;

class BackendAcl extends \Helper
{
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $role = $this->getDI()->getShared('auth')->getRole();
        if (!empty($role) && !in_array($role, ['guest', 'users'])) {
            $roles_permit = \App\Models\Roles::findFirst([
                'column' => 'modules',
                'conditions' => 'path=:role:',
                'bind' => [
                    'role' => $role
                ],
                'cache' => [
                    'key' => 'role.modules.' . $role
                ]
            ]);
            if (!$roles_permit) {
                $message = [
                    'status' => false,
                    'msg' => 'Not found'];
                $this->getDI()->get('resp')->getJson($message, 404);
                $event->stop();
                exit(0);
            }
            $controller = strtolower($dispatcher->getControllerName());
            $action = strtolower($dispatcher->getActionName());
            if ($controller == 'index' && $action == 'index') {
                return true;
            }
            $modules = \json_decode($roles_permit->modules, true);
            if (!empty($modules)) {
                if (!empty($modules[$controller]) && in_array($action, $modules[$controller])) {
                    return true;
                }
            }
        }
        $message = [
            'status' => false,
            'msg' => 'Not found'];
        $this->getDI()->get('resp')->getJson($message, 404);
        $event->stop();
        exit(0);
    }

}