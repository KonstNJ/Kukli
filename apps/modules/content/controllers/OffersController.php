<?php

namespace App\Content\Controllers;

use App\Models\Offers;

class OffersController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }


    public function listsAction()
    {
        $param = $this->getLimit();
        $query = new \Query();
        if($param->has('s'))
        {
            $sql = "select id, condition, category, brands, types, langOrDefault(content, :lang, 'name') as name, price, currency, getFirstImages(picture) as picture, date_release from offers, plainto_tsquery(:search) as search_query where search_tsv @@ search_query and banned is false and moderated is true and active is true and publish is true order by ts_rank(search_tsv, search_query) desc, date_uptop desc";
            $items = $query
                ->sql($sql, [
                    'lang' => $this->lang,
                    'search' => $param->get('s')
                ])
                ->paginate(
                    $param->get('page'),
                    $param->get('limit')
                );
        } else {
            $sql = "select id, condition, category, brands, types, langOrDefault(content, :lang, 'name') as name, price, currency, getFirstImages(picture) as picture, date_release from offers where banned is false and moderated is true and active is true and publish is true order by uptop desc, date_uptop desc , date_create desc";
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
        $item = Offers::findFirst([
            'conditions' => 'id=:item: and active is true and publish is true',
            'bind' => [
                'item' => $id,
            ]
        ]);
        if($item)
        {
            if($item->banned===true)
            {
                $this->resultError('Banned',403);
                return ;
            }
            if($item->moderated === false)
            {
                $this->resultError('Moderated', 403);
                return ;
            }
            $this->resultOk([
                'category' => $item->category,
                'brands' => $item->brands,
                'types' => $item->types,
                'params' => $this->decodeJsonb($item->params),
                'name' => $item->name,
                'content' => $item->content,
                'currency' => $item->currency,
                'price' => $item->price,
                'tags' => $this->decodeJsonb($item->tags),
                'date_end' => $item->date_end,
                'picture' => $this->decodeJsonb($item->picture),
                'delivery' => $item->delivery,
            ]);
            return ;
        }
        $this->resultError('Not found', 404);
    }

    public function getcodeAction(int $user, int $id)
    {
        $item = Offers::findFirst([
            'conditions' => 'id=:item: and users=:users: and active is true and publish is true',
            'bind' => [
                'item' => $id,
                'user' => $user
            ]
        ]);
        if($item)
        {
            if($item->banned===true)
            {
                $this->resultError('Banned',403);
                return ;
            }
            if($item->moderated === false)
            {
                $this->resultError('Moderated', 403);
                return ;
            }
            $this->resultOk([
                'category' => $item->category,
                'brands' => $item->brands,
                'types' => $item->types,
                'params' => $this->decodeJsonb($item->params),
                'name' => $item->name,
                'content' => $item->content,
                'currency' => $item->currency,
                'price' => $item->price,
                'tags' => $this->decodeJsonb($item->tags),
                'date_end' => $item->date_end,
                'picture' => $this->decodeJsonb($item->picture),
                'delivery' => $item->delivery,
            ]);
            return ;
        }
        $this->resultError('Not found', 404);
    }

}