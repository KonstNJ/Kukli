<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;
use App\Models\Traits\ListsFind;

class UsersFollow extends \Phalcon\Mvc\Model
{
    use ExtendModel;
    use ListsFind;

    /**
     * @var integer
     */
    public $id;

    /**
     * @var integer
     */
    public $users;

    /**
     * @var string
     */
    public $follow;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('db_users');
        $this->setSource("users_follow");
        $this->belongsTo('users', 'App\Models\Users', 'id', ['alias' => 'Users']);
    }

    public function test()
    {
        return $this->getHandler();
    }

    public function following(int $user, array $param = [])
    {
        $sql = "select id, fullname as name, getfirstimages(picture) as picture, type from users where id in(select getfollowingusers(:user))";
        $items = $this->sql($sql, ['user'=>$user])
            ->paginate($param['page'], $param['limit']);
        return $items;
    }

    /*public function following(int $user)
    {
        $db = $this->getReadConnection()->getInternalHandler();
        $stmt = $db->prepare("select id, fullname as name, getfirstimages(picture) as picture, type from users where id in(select getfollowingusers(:user))");
        $stmt->bindParam(':user', $user);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }*/

    public function setFollow(int $user, int $follow)
    {
        $db = $this->getReadConnection()->getInternalHandler();
        $stmt = $db->prepare("select addfollowingusers(:user, :follow)");
        $stmt->bindParam(':user', $user);
        $stmt->bindParam(':follow', $follow);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function recommendations()
    {}

    /**
     * Before save method for model.
     */
    public function beforeSave()
    {
        $this->users = $this->Jsonb($this->users);
    }

    /**
     * Before create method for model.
     */
    public function beforeCreate()
    {
        $this->users = $this->Jsonb($this->users);
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->users = $this->Jsonb($this->users);
    }
}