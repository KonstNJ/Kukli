<?php

namespace App\Api\Controllers;

use App\Models\Offers;
use App\Validation\OffersValidation;

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
            $sql = "select id, langOrDefault(content, :lang, 'name') as name, types, getFirstImages(picture) as picture, price, date_release, date_create, pick_up_point, active, publish, moderated, banned, favouritesitemcount('offers', id) as favourite from offers, plainto_tsquery(:search) as search_query where users=:user and search_tsv @@ search_query order by ts_rank(search_tsv, search_query) desc";
            $items = $query->sql($sql, [
                'user' => $this->user,
                'lang' => $this->lang,
                'search' => $param->get('s')
            ])->paginate(
                $param->get('page'),
                $param->get('limit')
            );
        } else {
            $sql = "select id, langOrDefault(content, :lang, 'name') as name, types, getFirstImages(picture) as picture, price, date_release, date_create, pick_up_point, active, publish, moderated, banned, favouritesitemcount('offers', id) as favourite from offers where users=:user order by date_create desc";
            $items = $query->sql($sql, [
                'user' => $this->user,
                'lang' => $this->lang,
            ])->paginate(
                $param->get('page'),
                $param->get('limit')
            );
        }
        $this->resultOk($items);
    }

    public function getAction(int $id)
    {
        $item = Offers::findFirst([
            'conditions' => 'id=:item: and users=:user:',
            'bind' => [
                'item' => $id,
                'user' => $this->user
            ]
        ]);
        if ($item)
        {
            $content = (\json_decode($item->content, true))['default'];
            $result = [
                'product_code' => $item->product_code,
                'condition' => (!\is_null($item->condition)) ? \json_decode($item->condition, true) : [],
                'category' => $item->category,
                'brands' => $item->brands,
                'types' => $item->types,
                'params' => (!\is_null($item->params)) ? \json_decode($item->params, true) : [],
                'name' => $content['name'],
                'content' => $content['content'],
                'currency' => $item->currency,
                'price' => $item->price,
                'price_old' => (!\is_null($item->price_old)) ? \json_decode($item->price_old, true) : [],
                'tags' => (!\is_null($item->tags)) ? \json_decode($item->tags, true) : [],
                'date_end' => $item->date_end,
                'picture' => (!\is_null($item->picture)) ? \json_decode($item->picture, true) : [],
                'videos' => (!\is_null($item->videos)) ? \json_decode($item->videos, true) : [],
                'delivery' => $item->delivery,
                'pick_up_point' => $item->pick_up_point,
                'active' => $item->active,
                'publish' => $item->publish,
                'date_release' => $item->date_release,
                'moderated' => $item->moderated,
                'banned' => $item->banned,
                'banned_msg' => $item->banned_msg,
            ];
            $this->resultOk($result);
            return ;
        }
        $this->resultError('Not found', 404);
    }

    public function saveAction()
    {
        if($this->request->isPost())
        {
            $messages = $this->validData(new OffersValidation(), $this->request->getPost());
            if(count($messages))
            {
                $this->resultError($messages);
                return ;
            }
            $item = $this->getDI()->getShared('model')::init(Offers::class, $this->getId());
            if($item)
            {
                $params = $this->parseParam($this->request->getPost('params'));
                $content['default'] = [
                    'name' => $this->request->getPost('name', 'name'),
                    'content' => $this->request->getPost('content', 'content')
                ];
                $item->assign([
                    'users' => $this->user,
                    'content' => $content,
                    'category' => $this->request->getPost('category', ['string','trim','']),
                    'brands' => $this->request->getPost('brands', 'int'),
                    'types' => $this->request->getPost('types', 'name'),
                    'params' => $params,
                    'currency' => $this->request->getPost('currency', 'currency'),
                    'price' => $this->request->getPost('price', 'int'),
                    'tags' => $this->request->getPost('tags', 'name'),
                    'date_end' => $this->request->getPost('date_end', 'date'),
                    //'videos' => $this->request->getPost('videos'),
                    'delivery' => $this->request->getPost('delivery', 'name'),
                    'pick_up_point' => $this->request->getPost('pick_up_point', 'bool'),
                    'active' => $this->request->getPost('active', 'bool'),
                    'publish' => $this->request->getPost('publish', 'bool'),
                    'date_release' => $this->request->getPost('date_release', 'date')
                ]);
                if($item->save())
                {
                    $images = $this->getDI()->get('images');
                    $dir_path = $this->user . '/offers/' . $item->id;
                    $picture = $images->add($this->request, $dir_path);
                    if(!\empty($picture))
                    {
                        if(!\is_null($item->picture))
                        {
                            $picture = \array_merge($picture, \json_decode($item->picture, true));
                        }
                        $item->picture = $picture;
                        $item->update();
                    }
                    $this->resultOk([
                        'condition' => $item->condition,
                        'category' => $item->category,
                        'brands' => $item->brands,
                        'types' => $item->types,
                        'params' => $params,
                        'name' => $content['default']['name'],
                        'content' => $content['default']['content'],
                        'currency' => $item->currency,
                        'price' => $item->price,
                        'picture' => $picture,
                        'delivery' => $item->delivery,
                        'pick_up_point' => $item->pick_up_point,
                        'active' => $item->active,
                        'publish' => $item->publish,
                        'date_release' => $item->date_release,
                    ]);
                    return ;
                }
            }
        }
        $this->resultError();
    }

    public function deleteAction(int $id)
    {
        $item = Offers::findFirst([
            'conditions' => 'id=:item: and users=:user:',
            'bind' => [
                'item' => $id,
                'user' => $this->user
            ]
        ]);
        if($item)
        {
            if(!$item->delete())
            {
                $this->resultError();
                return ;
            }
            $this->resultOk();
            return ;
        }
        $this->resultError('Not found', 404);
    }

}