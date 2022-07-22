<?php

namespace App\Api\Controllers;

use App\Models\UsersFollow;
use App\Validation\UseridValidation;
use Phalcon\Db\Enum;

class FollowController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function deleteAction()
    {
        if($this->request->isPost())
        {
            $messages = $this->validData(new UseridValidation(), $this->request->getPost());
            if(count($messages))
            {
                $this->resultError($messages);
                return ;
            }
            $item = $this->getDI()->get('db_users')
                ->query("select deletefollowingusers(:user, :follow)", [
                    'user' => $this->user,
                    'follow' => $this->request->getPost('user', 'int'),
                ])
                ->fetch(Enum::FETCH_ASSOC);
            if(!empty($item['deletefollowingusers']))
            {
                $this->resultOk();
                return;
            }
        }
        $this->resultError();
    }

    public function addAction()
    {
        if($this->request->isPost())
        {
            $messages = $this->validData(new UseridValidation(), $this->request->getPost());
            if(count($messages))
            {
                $this->resultError($messages);
                return ;
            }
            $item = $this->getDI()->get('db_users')
                ->query("select addfollowingusers(:user, :follow)", [
                    'user' => $this->user,
                    'follow' => $this->request->getPost('user', 'int'),
                ])
                ->fetch(Enum::FETCH_ASSOC);
            if(!empty($item['addfollowingusers']))
            {
                $this->resultOk();
                return;
            }
        }
        $this->resultError();
    }

    public function recommendationsAction()
    {
        $db = $this->getDI()->get('db_users');
        $interests = $db->query("select interests, (select follow from users_follow) as follow from users where id=:uid", ['uid'=>$this->user])->fetch(Enum::FETCH_ASSOC);
        $sql = "select id, coalesce(fullname, name) as name, getfirstimages(picture) as picture, type from users, interestsrank(:interests, interests) as rank where id not in (select * from users_jsonb_lists(:follow)) and rank > 1 order by rank desc limit 5";
        $items = $db->query($sql, [
            'interests' => $interests['interests'],
            'follow' => $interests['follow']
        ])->fetchAll(Enum::FETCH_ASSOC);
        $this->resultOk($items);
    }

    public function followingAction()
    {
        $sql = "select id, coalesce(fullname, name) as name, getfirstimages(picture) as picture, type from users where id in(select getfollowingusers(:user))";
        $param = $this->getLimit();
        $query = new \Query('db_users');
        $items = $query
            ->sql($sql, ['user'=>$this->user])
            ->paginate(
                $param->get('page'),
                $param->get('limit')
            );
        $this->resultOk($items);
    }

    public function followersAction()
    {
        $sql = "select id, coalesce(fullname, name) as name, getfirstimages(picture) as picture, type from users where id in (select getfollowersusers(:user))";
        $param = $this->getLimit();
        $query = new \Query('db_users');
        $items = $query
            ->sql($sql, ['user'=>$this->user])
            ->paginate(
                $param->get('page'),
                $param->get('limit')
            );
        $this->resultOk($items);
    }
}