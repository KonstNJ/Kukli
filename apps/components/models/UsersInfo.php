<?php

class UsersInfo extends \Helper
{
    public function short(int $users)
    {
        $item = $this->getDI()
            ->get('db_users')
            ->query("select id, case when status is not null then getUsersStatus(), fullname, getFirstImages(picture) as picture, gender from users where id=:item and banned is false")
            ->fetch(\Phalcon\Db\Enum::FETCH_ASSOC);
    }
}