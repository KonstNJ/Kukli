<?php

namespace App\Content\Controllers;

use Phalcon\Db\Enum;

class FiltersController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function searchAction()
    {
        $param = $this->getLimit();
        $sql = "with items as (
            (select id, 'products' as types, langOrFirst(content, :lang, 'en', 'name') as name, getFirstImages(picture) as picture, price, favourite_exists('products', id, :uid) as favorite from products)
            union all
            (select id, 'offers' as types, langOrFirst(content, :lang, 'en', 'name') as name, getFirstImages(picture) as picture, price, favourite_exists('offers', id, :uid) as favorite from offers)
            union all
            (select id, 'album' as types, langOrDefault(name, :lang) as name, getAlbumPicture(id) as picture, null::integer as price, favourite_exists('album', id, :uid) as favorite from album)
        ) select i.*, coalesce(e.likes, 0) as likes, coalesce(e.comments, 0) as comments, coalesce(e.value, 0) as raiting
          from items as i left join entity_vote as e on e.entity=i.types and e.entity_id=i.id
          order by raiting desc";
        $query = new \Query();
        $items = $query->sql($sql, [
            'lang' => $this->lang,
            'uid' => $this->user
        ])->paginate(
            $param->get('page'),
            $param->get('limit')
        );
        $this->resultOk($items);
    }

    public function paramsAction()
    {
        $result = [];
        $sql = [
            'category' => "select id, parent, langOrFirst(name, :lang, 'en') from category order by treepath",
            'heroes' => "select id, langOrFirst(name, :lang, 'en') as name from heroes order by name",
            'producer' => "select id, langOrFirst(name, :lang, 'en') as name from producer order by name",
            'entity_type' => "select id, langOrFirst(name, :lang, 'en') as name from entity_type order by name",
            'brands' => "select id, concat_ws(' ', langOrFirst(name, :lang, 'en'), date_release) as name from brands order by name",
            'brands_universe' => "select id, concat_ws(' ', langOrFirst(name, :lang, 'en'), date_release) as name from brands_universe order by name",
        ];
        $this->db->begin();
        $result['price'] = $this->db
            ->query("with prices as (
                    (select min(price) as min_price, max(price) as max_price from products where active is true)
                    union all
                    (select min(price) as min_price, max(price) as max_price from offers where active is true and publish is true and moderated is true and banned is false)
                ) select min(min_price) as min, max(max_price) as max from prices")
            ->fetch(Enum::FETCH_ASSOC);
        foreach (new \ArrayIterator($sql) as $model => $query)
        {
            $request = $this->db->query($query, ['lang'=>$this->lang])->fetchAll(Enum::FETCH_ASSOC);
            if(!empty($request))
            {
                $result[$model] = $request;
            }
        }
        $this->db->commit();
        $this->resultOk($result);
    }

}