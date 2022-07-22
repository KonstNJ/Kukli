<?php

namespace App\Content\Controllers;

use App\Models\Users;

class UsersController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function listsAction()
    {
        $sql = "select id, type, coalesce(fullname, name) as name, getFirstImages(picture) as picture, isFollowing(:user, id) as following from users, get_max_follow(id) as rank where moderated is true and banned is false and roles is null order by rank desc , name";
        $param = $this->getLimit();
        $items = (new \Query('db_users'))->sql($sql, [
            'user' => $this->user
        ])->paginate(
            $param->get('page'),
            $param->get('limit')
        );
        $this->resultOk($items);
    }

    public function getAction(int $id)
    {
        $item = Users::findFirst([
            'conditions' => "id=:conditions:",
        ]);
        if (!$item)
        {
            $this->resultError('Not found', 404);
            return ;
        }
        if($item->banned===true)
        {
            $this->resultError('Banned',403);
            return ;
        }
        if($item->moderated === false)
        {
            $this->resultError('Moderated', 403);
            return ;
        }
        $result = [
            ''
        ];
    }

    private function offset(int $current = 1, int $limit=25)
    {
        $count = Users::count([
            'conditions' => "moderated='t' and banned='f' and roles is null",
            'cache' => [
                'key' => 'users.count'
            ]
        ]);
        $result = new \stdClass();
        $result->total_count = $count;
        $result->last = ($last = \floor($count / $limit)) > 0 ? $last : 1;
        $result->current = $result->last >= $current
            ? $current
            : $result->last;
        $result->previous = $result->current > 1
            ? $result->current - 1
            :  1;
        $result->next = $result->last > $result->current
            ? $result->current + 1
            : $result->current;
    }

}