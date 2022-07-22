<?php

namespace App\Api\Controllers;

use App\Models\Shops;
use App\Validation\ShopsValidation;

class ShopsController extends ControllerBase
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
            $sql = "select s.id, langOrDefault(s.content, :lang, 'name') as name, getFirstImages(s.picture) as picture, s.date_create, s.moderated, s.banned, s.banned_msg from shops as s, plainto_tsquery(:search) as search_query where s.users=:user and s.search_tsv @@ search_query order by ts_rank(s.search_tsv, search_query) desc";
            $items = $query->sql($sql, [
                'search'=>$param->get('s'),
                'lang' => $this->lang,
                'user'=>$this->user,
            ])->paginate(
                $param->get('page'),
                $param->get('limit')
            );
        } else {
            $sql = "select id, langOrDefault(content, :lang, 'name') as name, getFirstImages(picture) as picture, date_create, moderated, banned, banned_msg from shops where users=:user order by date_create desc limit :limit offset :page";
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
        $item = Shops::findFirst([
            'conditions' => 'id=:item: and users=:user:',
            'bind' => [
                'item' => $id,
                'user' => $this->user
            ]
        ]);
        if($item)
        {
            $result = $item->toArray();
            $content = (\json_decode($result['content'], true))['default'];
            $result['name'] = $content['name'];
            $result['content'] = $content['content'];
            $this->resultOk($result);
            return ;
        }
        $this->resultError();
    }

    public function saveAction()
    {
        if($this->request->isPost())
        {
            $messages = $this->validData(new ShopsValidation(), $this->request->getPost());
            if(count($messages))
            {
                $this->resultError($messages);
                return ;
            }
            $item = $this->getDI()->getShared('model')::init(Shops::class, $this->getId());
            if($item)
            {
                $item->assign([
                    'users' => $this->user,
                    'content' => [
                        'default'=>[
                            'name' => $this->request->getPost('name'),
                            'content' => $this->request->getPost('content'),
                        ]
                    ],
                ]);
                if ($item->save())
                {
                    $images = $this->getDI()->get('images');
                    $dir_path = $this->user . '/shops/' . $item->id;
                    $picture = $images->add($this->request, $dir_path);
                    if(!empty($picture))
                    {
                        if(!is_null($item->picture))
                        {
                            $picture = array_merge($picture, json_decode($item->picture, true));
                        }
                        $item->picture = $picture;
                        $item->update();
                    }
                    $content = [];
                    if(\is_string($item->content))
                    {
                        $content = (\json_decode($item->content, true))['default'];
                    }
                    if(\is_array($item->content))
                    {
                        $content = $item->content['default'];
                    }
                    $this->resultOk([
                        'id' => $item->id,
                        'name' => $content['name'],
                        'content' => $content['content'],
                        'picture' => $picture,
                        'date_create' => $item->date_create,
                        'moderated' => $item->moderated
                    ]);
                    return ;
                }
            }
        }
        $this->resultError();
    }

    public function deleteAction(int $id)
    {
        $item = Shops::findFirst([
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