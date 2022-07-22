<?php

namespace App\Api\Controllers;

use App\Models\Reviews;
use App\Validation\ReviewsValidation;

class ReviewsController extends ControllerBase
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
            $sql = "select id, langOrDefault(content, :lang) as content, getFirstImages(picture) as picture, date_create, banned, banned_msg from reviews, plainto_tsquery(:search) as search_query where users=:user and search_tsv @@ search_query order by ts_rank(search_tsv, search_query) desc";
            $items = $query->sql($sql, [
                'search'=>$param->get('s'),
                'user'=>$this->user,
                'lang' => $this->lang,
            ])->paginate($param->get('page'), $param->get('limit'));
        } else {
            $sql = "select id, langOrDefault(content, :lang) as content, getFirstImages(picture) as picture, date_create, banned, banned_msg from reviews where users=:user order by date_create desc";
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
        $item = Reviews::findFirst([
            'conditions' => 'id=:item: and users=:user:',
            'bind' => [
                'item' => $id,
                'user' => $this->user,
            ]
        ]);
        if($item)
        {
            $content = \json_decode($item->content, true);
            $picture = [];
            if(!\is_null($item->picture))
            {
                $picture = \json_decode($item->picture, true);
            }
            $result = [
                'id' => $item->id,
                'content' => $content['default'],
                'picture' => $picture,
                'date_create' => $item->date_create,
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
            $messages = $this->validData(new ReviewsValidation(), $this->request->getPost());
            if(count($messages))
            {
                $this->resultError($messages);
                return ;
            }
            $item = $this->getDI()->getShared('model')::init(Reviews::class, $this->getId());
            if($item)
            {
                $content = $this->request->getPost('content');
                $item->assign([
                    'users' => $this->user,
                    'content' => ['default' => $content],
                ]);
                if ($item->save())
                {
                    $images = $this->getDI()->get('images');
                    $dir_path = $this->user . '/reviews/' . $item->id;
                    $picture = $images->add($this->request, $dir_path);
                    if(!empty($picture))
                    {
                        if(!\is_null($item->picture))
                        {
                            $picture = \array_merge($picture, \json_decode($item->picture, true));
                        }
                        $item->picture = $picture;
                        $item->update();
                    }
                    $this->resultOk([
                        'id' => $item->id,
                        'content' => $content,
                        'picture' => $picture,
                        'date_create' => $item->date_create,
                        'banned' => $item->banned,
                        'banned_msg' => $item->banned_msg
                    ]);
                    return ;
                }
            }
        }
        $this->resultError();
    }

    public function deleteAction(int $id)
    {
        $item = Reviews::findFirst([
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