<?php

namespace App\Content\Controllers;

use Phalcon\Db\Enum;

class CountryController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function getAction()
    {
        $sql = "select id, nicename as name, iso as code from country order by name";
        $items = $this->db->query($sql)->fetchAll(Enum::FETCH_ASSOC);
        $this->resultOk($items);
    }

    public function itemAction()
    {
        $sql = "select * from country where name % :lang";
    }

}