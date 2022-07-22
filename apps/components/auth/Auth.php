<?php
use App\Models\Users;
class Auth extends \Helper
{
    /**
     * @param Users $users
     * @return array
     */
    public function saveDate(Users $users)
    {
        $users_role = 'users';
        $stat = (!\is_null($users)) ? $users->getUsersStats() : new \stdClass();
        $users_data = [
            'name' => $users->name,
            'picture' => (!\is_null($users->picture)) ? \json_decode($users->picture, true)[0] : null,
            'type' => $users->type,
            'following' => $stat->following ?? 0,
            'followers' => $stat->followers ?? 0
        ];
        if(!\is_null($users->roles))
        {
            $roles = $users->getRoles();
            $users_role = $roles->path;
            $modules = \json_encode($roles->modules, true);
            $publicModules = $this->getDI()->get('config')->publicModules;
            foreach (new \ArrayIterator($modules) as $role => $editable)
            {
                if(\in_array($role, $publicModules) && \in_array($editable, ['save','delete','moderated','banned','unbanned']))
                {
                    $users_data['editable'][] = $role;
                }
            }
        }
        $this->getDI()
            ->getShared('token')
            ->auth($users->ident,$users_role,$users->id);
        return $users_data;
    }

    /**
     * @return string|false
     */
    public function getUuid()
    {
        if($users_data = $this->getUsersData())
        {
            return $users_data['uuid'];
        }
        return false;
    }

    /**
     * @return string|false
     */
    public function getId()
    {
        if($users_data = $this->getUsersData())
        {
            return $users_data['id'];
        }
        return false;
    }

    /**
     * @return string|false
     */
    public function getRole()
    {
        if($users_data = $this->getUsersData())
        {
            return $users_data['roles'];
        }
        return false;
    }

    /**
     * @return array|false
     */
    public function getUsersData()
    {
        $persistent = $this->getDI()->getShared('sessionBag');
        if($persistent->has('users'))
        {
            return $persistent->get('users');
        }
        return false;
    }
}