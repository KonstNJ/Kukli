<?php

namespace App\Backend\Controllers;

use App\Models\Category;
use App\Validation\CategoryValidation;
use Phalcon\Db\Enum;

class CategoryController extends ControllerBase
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
            'treepath' => 'treepath',
            'counts' => 'counts',
        ];
        $params = $this->getDI()->get('params')->get(true);
        $sort_param = (new \Sorts($sort_default, 'treepath', 'asc'))
            ->query($params)
            ->get();
        if(!empty($sort_param['s']))
        {
            $sql = "with items as (select id, parent, treepath, nlevel(treepath) as level, el.value::text as val, name, getFirstImages(picture) as picture from category, jsonb_each(name) as el) select distinct id, parent, treepath, level, coalesce(name->>:lang, name->>'en') as name from items where val % :search order by ".$sort_param['order'];
            $items = (new \Query())->sql($sql, ['lang'=>$this->lang, 'search'=>$sort_param['s']])
                ->paginate($sort_param['current'], $sort_param['limit']);
        } else {
            $sql = "select id, parent, nlevel(treepath) as level, coalesce(name->>:lang, name->>'en') as name, getFirstImages(picture) as picture from category order by ".$sort_param['order'];
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
            $messages = $this->validData(new CategoryValidation(), $this->request->getPost());
            if(count($messages))
            {
                foreach ($messages as $message)
                {
                    $this->flashSession->error($message);
                }
                $this->view->data = $this->request->getPost();
                return ;
            }
            $msg = 'save_error';
            $status = false;
            $id = $this->request->getPost('id', 'int');
            $item = \ModelControl::init(Category::class, $id);
            if($item)
            {
                $item->assign([
                    'parent' => $this->request->getPost('parent', 'int', 0),
                    'name' => $this->request->getPost('name', ['trim', 'string'])
                ]);
                if($item->save())
                {
                    $images = $this->getDI()->get('images');
                    $picture = $images->add($this->request, 'category/'.$item->id.'/');
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
            $item = Category::findFirstById($id);
            if(!$item)
            {
                return $this->notice('error', $this->local->_('not_found'), $this->initUrl());
            }
            $this->view->data = $item->toArray();
        }
        $this->view->langs = $this->initLangs();
        $this->view->path_lists = $this->db->query("select id, parent, treepath, nlevel(treepath) as level, coalesce(name->>:lang, name->>'en') as name from category order by treepath, parent nulls first ", ['lang'=>$this->lang])->fetchAll(Enum::FETCH_ASSOC);
    }

    /**
     * @param int|null $id
     * @return void
     */
    public function deleteAction(?int $id = null)
    {
        $result = $this->delete(Category::class, $id);
        $msg = $result
            ? $this->dictionary->_('delete_ok')
            : $this->dictionary->_('delete_error');
        $this->returnNotice($result, $msg, $this->initUrl());
    }
}