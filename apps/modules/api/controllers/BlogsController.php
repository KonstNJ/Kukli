<?php

namespace App\Api\Controllers;

use App\Models\Blogs;
use App\Validation\BlogsValidation;
use Phalcon\Db\Enum;

class BlogsController extends ControllerBase
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
            $sql = "select b.id, langOrDefault(b.content, :lang) as content, getFirstImages(b.picture) as picture, b.date_create, b.moderated, b.banned, b.banned_msg from blogs as b, plainto_tsquery(:search) as search_query where b.users=:user and b.search_tsv @@ search_query order by ts_rank(b.search_tsv, search_query) desc";
            $items = $query->sql($sql, [
                'search'=>$param->get('s'),
                'user'=>$this->user,
                'lang' => $this->lang,
            ])->paginate($param->get('page'), $param->get('limit'));
        } else {
            $sql = "select id, langOrDefault(content, :lang) as content, getFirstImages(picture) as picture, date_create, moderated, banned, banned_msg from blogs where users=:user order by date_create desc";
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
        $item = Blogs::findFirst([
            'conditions' => 'id=:item: and users=:user:',
            'bind' => [
                'item' => $id,
                'user' => $this->user
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
            $messages = $this->validData(new BlogsValidation(), $this->request->getPost());
            if(count($messages))
            {
                $this->resultError($messages);
                return ;
            }
            $item = $this->getDI()->getShared('model')::init(Blogs::class, $this->getId());
            //$item = \ModelControl::init(Blogs::class, $this->getId());
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
                    $dir_path = $this->user . '/blogs/' . $item->id;
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
        $item = Blogs::findFirst([
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