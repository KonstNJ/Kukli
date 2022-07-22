<?php

namespace App\Content\Controllers;

use App\Models\Blogs;
use Phalcon\Db\Enum;

class BlogsController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function listsAction()
    {
        $sql = "select id, langOrDefault(content, :lang) as content, getFirstImages(picture) as picture, date_create from blogs where banned is false and moderated is true order by date_create desc";
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
        $sql = "select id, langOrDefault(content, :lang) as content, picture, date_create from blogs where id=:item and moderated is true and banned is false";
        $item = $this->db->query($sql, [
            'lang' => $this->lang,
            'item' => $id,
        ])->fetch(Enum::FETCH_ASSOC);
        if(empty($item))
        {
            $this->resultError('Not found', 404);
            return ;
        }
        $item['picture'] = $this->decodeJsonb($item['picture']);
        $this->resultOk($item);
    }

}