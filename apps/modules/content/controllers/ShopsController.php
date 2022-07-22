<?php

namespace App\Content\Controllers;

class ShopsController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function listsAction()
    {
        $sql = "select id, langOrDefault(content, :lang, 'name') as name, getFirstImages(picture) as picture, date_create from shops where banned is false and moderated is true order by date_create";
        $param = $this->getLimit();
        $items = (new \Query())
            ->sql($sql, ['lang'=>$this->lang])
            ->paginate(
                $param->get('page'),
                $param->get('limit')
            );
        $this->resultOk($items);
    }

    public function getAction(int $id)
    {
        $sql = "select id, c.name, c.content, picture, date_create from shops, jsonb_to_record(langOrFirstJsonb(content, :lang, 'en')) as c(name text, content text) where id=:item and banned is false and moderated is true";
        $item = (new \Query())->sql($sql, ['lang'=>$this->lang, 'item'=>$id])->fetch();
        if(empty($item))
        {
            $this->resultError('Not found', 404);
            return ;
        }
        $item['picture'] = $this->decodeJsonb($item['picture']);
        $this->resultOk($item);
    }

}