<?php

namespace App\Content\Controllers;

class ProductsController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function listsAction()
    {
        $param = $this->getLimit();
        $query = new \Query();
        if(\is_numeric($this->user))
        {
            $sql = "select id, langOrFirst(content, :lang, 'en', 'name') as name, getFirstImages(picture) as picture, price, favourite_exists('products', id, :uid) as favorite, raiting from products, get_vote('products', id) as raiting where active is true order by raiting desc, date_create desc";
            $items = $query
                ->sql($sql, [
                    'lang' => $this->lang,
                    'uid' => $this->user
                ])
                ->paginate(
                    $param->get('page'),
                    $param->get('limit')
                );
        } else {
            $sql = "select id, langOrFirst(content, :lang, 'en', 'name') as name, getFirstImages(picture) as picture, price, false as favorite, raiting from products, get_vote('products', id) as raiting where active is true order by raiting desc, date_create desc";
            $items = $query
                ->sql($sql, [
                    'lang' => $this->lang,
                ])
                ->paginate(
                    $param->get('page'),
                    $param->get('limit')
                );
        }

        $this->resultOk($items);
    }

    public function getAction(int $id)
    {
        $sql = "select id, entity_type, category, heroes, producer, brands, brands_universe, currency, hashtag, params, media, c.name, c.content, price, oldprice, picture from products, jsonb_to_record(langOrFirstJsonb(content, :lang, 'en')) as c(name text, about text, content text) where id=:item and active is true";
        $item = (new \Query())->sql($sql, ['lang'=>$this->lang, 'item'=>$id])->fetch();
        if(empty($item))
        {
            $this->resultError('Not found', 404);
            return ;
        }
        $item['params'] = $this->decodeJsonb($item['params']);
        $item['oldprice'] = $this->decodeJsonb($item['oldprice']);
        $item['hashtag'] = $this->decodeJsonb($item['hashtag']);
        $item['picture'] = $this->decodeJsonb($item['picture']);
        $item['media'] = $this->decodeJsonb($item['media']);
        $this->resultOk($item);
    }
}