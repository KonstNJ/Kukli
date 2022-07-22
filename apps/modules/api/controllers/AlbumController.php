<?php

namespace App\Api\Controllers;

use App\Models\Album;
use App\Models\Pictures;

class AlbumController extends ControllerBase
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
            $sql = "select id, name, date_create, moderated, banned, banned_msg, getAlbumPictures(id) as pictures from album where users=:user and name % :search order by similarity(name, :search) desc ";
            $items = $query->sql($sql, [
                'user' => $this->user,
                'search' => $param->get('s')
            ])->paginate(
                $param->get('page'),
                $param->get('limit')
            );
        } else {
            $sql = "select id, name, date_create, moderated, banned, banned_msg, getAlbumPictures(id) as pictures from album where users=:user order by date_create desc ";
            $items = $query->sql($sql, [
                'user' => $this->user
            ])->paginate(
                $param->get('page'),
                $param->get('limit')
            );
        }
        if(!empty($items))
        {
            $iter = [];
            foreach (new \ArrayIterator($items->items) as $item)
            {
                $item['pictures'] = \json_decode($item['pictures'], true);
                $iter[] = $item;
            }
            $items->items = $iter;
        }
        $this->resultOk($items);
    }

    public function getAction(int $id)
    {
        $item = Album::findFirst([
            'conditions' => 'id=:item: and users=:user:',
            'bind' => [
                'item' => $id,
                'user' => $this->user
            ]
        ]);
        if($item)
        {
            $this->resultOk([
                'id' => $item->id,
                'name' => $item->name,
                'pictures' => \json_decode($item->getPictures()->picture, true)
            ]);
            return ;
        }
        $this->resultError('Not found', 404);
    }

    public function saveAction()
    {
        if($this->request->isPost())
        {
            $time_set = (new \DateTimeImmutable());
            $images = $this->getDI()->get('images');
            $dir_path = $this->user . '/pictures/' . $time_set->getTimestamp();
            $picture = $images->add($this->request, $dir_path);
            if(!empty($picture))
            {
                $item = $this->getDI()->getShared('model')::init(Album::class, $this->getId());
                //$item = \ModelControl::init(Album::class, $this->getId());
                if($item)
                {
                    $name = $this->decodeJsonb($item->name);
                    $name['default'] = $this->request->getPost('name', 'name', $time_set);
                    $item->assign([
                        'users' => $this->user,
                        'name' => $name,
                        'date_create' => $time_set->format('Y-m-d H:i:s.u')
                    ]);
                    $file_model = new Pictures();
                    $file_model->assign([
                        'users' => $this->user,
                        'picture' => $picture
                    ]);
                    $item->Pictures = $file_model;
                    if($item->save())
                    {
                        $this->resultOk([
                            'id' => $item->id,
                            'name' => $item->name,
                            'pictures' => \json_decode($item->getPictures()->picture, true)
                        ]);
                        return ;
                    }
                }
            }
        }
        $this->resultError();
    }

    public function deleteAction(int $id)
    {
        $item = Album::findFirst([
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
        $this->resultError();
    }
}