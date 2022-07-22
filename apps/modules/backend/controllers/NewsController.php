<?php

namespace App\Backend\Controllers;


use App\Models\News;
use App\Validation\NewsValidation;

class NewsController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function viewAction()
    {
        $sort_default = [
            'id' => 'id',
            'name' => 'name',
            'active' => 'active',
            'date' => 'date_create',
        ];
        $params = $this->getDI()->get('params')->get(true);
        $sort_param = (new \Sorts($sort_default, 'date', 'desc'))
            ->query($params)
            ->get();
        if(!empty($sort_param['s']))
        {
            $sql = "with items as (select id, el.value->>'name' as name, el.value->>'anons' as anons, el.value->>'content' as content, getFirstImages(picture) as picture, active, date_create from news, jsonb_each(content) as el ) select distinct id, name, anons, picture, active, date_create from items where name % :search or content % :search order by ".$sort_param['order'];
            $items = (new \Query())->sql($sql, ['search'=>$sort_param['s']])
                ->paginate($sort_param['current'], $sort_param['limit']);
        } else {
            $sql = "select id, coalesce((content->>:lang)::jsonb->>'name', (content->>'en')::jsonb->>'name') as name, coalesce((content->>:lang)::jsonb->>'anons', (content->>'en')::jsonb->>'anons') as anons, getFirstImages(picture) as picture, active, date_create from news order by ".$sort_param['order'];
            $items = (new \Query())->sql($sql, ['lang'=>$this->lang])
                ->paginate($sort_param['current'], $sort_param['limit']);
        }
        $this->view->search_param = $sort_param['s'] ?? null;
        $this->view->data = $items;
        $this->view->sorts = $this->sortDesc(array_keys($sort_default));
    }

    public function saveAction(?int $id = null)
    {
        if($this->request->isPost())
        {
            $messages = $this->validData(new NewsValidation(), $this->request->getPost());
            if(count($messages))
            {
                foreach ($messages as $message)
                {
                    $this->flashSession->error($message);
                }
                $this->view->langs = $this->initLangs();
                $this->view->data = $this->request->getPost();
                return ;
            }
            $msg = 'save_error';
            $status = false;
            $id = $this->request->getPost('id', 'int');
            $item = \ModelControl::init(News::class, $id);
            if($item)
            {
                $item->assign([
                    'content' => $this->request->getPost('content'),
                    'active' => $this->request->getPost('active', 'bool'),
                ]);
                if($item->save())
                {
                    $images = $this->getDI()->get('images');
                    $picture = $images->add($this->request, 'news/'.$item->id);
                    if(!empty($picture))
                    {
                        if(!is_null($item->picture))
                        {
                            $old_picture = $this->request->getPost('old_picture') ?? [];
                            $images->imagesDiffDel(json_decode($item->picture, true), $old_picture);
                            if(!empty($old_picture))
                            {
                                $picture = array_merge($picture, $old_picture);
                            }
                        }
                        $item->picture = $picture;
                        $item->update();
                    }
                    $msg = 'save_ok';
                    $status = true;
                }
            }
            $this->returnNotice($status, $this->local->_($msg), $this->initUrl());
            return ;
        }
        if(!is_null($id))
        {
            $item = News::findFirstById($id);
            if(!$item)
            {
                return $this->notice('error', $this->local->_('not_found'), $this->initUrl());
            }
            $this->view->data = $item->toArray();
        }
        $this->view->langs = $this->initLangs();
    }

    /**
     * @param string $type
     * @param int|null $id
     * @return void
     */
    public function manipulationAction(string $type, ?int $id = null)
    {
        $msg = 'update_error';
        $status = false;
        if($ids = $this->getId($id))
        {
            $active = ($type=='hiden') ? 'f' : 't';
            $sql = sprintf("update news set active='%s' where id in(%s)", $active, implode(',', (array) $ids));
            if($this->db->query($sql)->execute())
            {
                $msg = 'update_ok';
                $status = true;
            }
        }
        $this->returnNotice($status, $this->local->_($msg), $this->initUrl());
    }

    /**
     * @param int|null $id
     * @return void
     */
    public function deleteAction(?int $id = null)
    {
        $result = $this->delete(News::class, $id);
        $msg = $result
            ? $this->local->_('delete_ok')
            : $this->local->_('delete_error');
        $this->returnNotice($result, $msg, $this->initUrl());
    }

}