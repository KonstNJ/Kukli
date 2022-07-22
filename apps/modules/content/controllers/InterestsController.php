<?php

namespace App\Frontend\Controllers;

use Phalcon\Db\Enum;

class InterestsController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function getAction()
    {
        $sql = "select id, coalesce(name->>:lang, name->>'en') as name, getFirstImages(picture) as picture from interests order by position, id";
        $items = $this->db->query($sql, ['lang'=>$this->lang])->fetchAll(Enum::FETCH_ASSOC);
        $this->resultOk($items);
    }
}