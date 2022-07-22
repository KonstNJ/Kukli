<?php

namespace App\Content\Controllers;

use App\Models\Languages;
use Phalcon\Db\Enum;

class LangsController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function listsAction()
    {
        $langs = $this->db
            ->query("select name, code from languages order by defaults desc")
            ->fetchAll(Enum::FETCH_ASSOC);
        $this->resultOk($langs);
    }

    public function setAction()
    {
        $lang = $this->dispatcher->getParam('lang');
        $this->persistent->set('lang', $lang);
        $this->resultOk();
    }
}