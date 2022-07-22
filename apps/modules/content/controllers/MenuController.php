<?php

namespace App\Content\Controllers;

use Phalcon\Db\Enum;

class MenuController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function getAction()
    {
        $sql = "select id, parent, nlevel(treepath) as level, menu, treepath, langOrFirst(content, :lang, 'en', 'name') as name from pages where active is true order by treepath, parent nulls first";
        $items = $this->db->query($sql, ['lang'=>$this->lang])->fetchAll(Enum::FETCH_ASSOC);
        $this->resultOk($items);
    }

    public function breadcrumbsAction()
    {
        if($this->request->hasQuery('id'))
        {
            $id = $this->request->getQuery('id', 'int');
            $type = $this->request->getQuery('type');
            if(!in_array($type, ['page','category']))
            {
                $type = 'page';
            }
            $sql = $type=='category'
                ? "select * from breadCategory(:item, :lang)"
                : "select * from breadcrumbs(:item, :lang)";
            $items = $this->db->query($sql, ['item'=>$id, 'lang'=>$this->lang])->fetchAll(Enum::FETCH_ASSOC);
            $this->resultOk($items);
        }
    }
}