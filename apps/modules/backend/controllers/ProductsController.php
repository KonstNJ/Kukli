<?php

namespace App\Backend\Controllers;

use App\Models\Products;
use App\Validation\ProductsValidation;
use Phalcon\Db\Enum;

class ProductsController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * @return void
     */
    public function viewAction()
    {
        $sort_default = [
            'id' => 'id',
            'name' => 'name',
            'active' => 'active',
            'price' => 'price',
            'date' => 'date_create',
        ];
        $params = $this->getDI()->get('params')->get(true);
        $sort_param = (new \Sorts($sort_default, 'date', 'desc'))
            ->query($params)
            ->get();
        if(!empty($sort_param['s']))
        {
            $sql = "with items as (select id, price, currency, oldprice, el.value->>'name' as name, el.value->>'content' as content, getFirstImages(picture) as picture, active, date_create from products, jsonb_each(content) as el ) select distinct id, price, currency, oldprice, name, picture, active, date_create from items where name % :search or content % :search order by ".$sort_param['order'];
            $items = (new \Query())->sql($sql, ['search'=>$sort_param['s']])
                ->paginate($sort_param['current'], $sort_param['limit']);
        } else {
            $sql = "select id, price, currency, coalesce((content->>:lang)::jsonb->>'name', (content->>'en')::jsonb->>'name') as name, getFirstImages(picture) as picture, active, date_create from products order by ".$sort_param['order'];
            $items = (new \Query())->sql($sql, ['lang'=>$this->lang])
                ->paginate($sort_param['current'], $sort_param['limit']);
        }
        $this->view->search_param = $sort_param['s'] ?? null;
        $this->view->data = $items;
        $this->view->sorts = $this->sortDesc(array_keys($sort_default));
    }

    /**
     * @param int|null $id
     * @return \Phalcon\Http\ResponseInterface|void
     */
    public function saveAction(?int $id = null)
    {
        if($this->request->isPost())
        {
            $messages = $this->validData(new ProductsValidation(), $this->request->getPost());
            if(count($messages))
            {
                foreach ($messages as $message)
                {
                    $this->flashSession->error($message);
                }
                $this->view->langs = $this->initLangs();
                $this->view->data = $this->request->getPost();
                $this->view->categories = $this->getCategory();
                return ;
            }
            $msg = 'save_error';
            $status = false;
            $id = $this->request->getPost('id', 'int');
            $item = \ModelControl::init(Products::class, $id);
            if($item)
            {
                $item->assign([
                    'content' => $this->request->getPost('content'),
                    'category' => $this->request->getPost('category', 'int', 0),
                    'heroes' => $this->request->getPost('heroes', 'int', 0),
                    'producer' => $this->request->getPost('producer', 'int', 0),
                    'brands' => $this->request->getPost('brands', 'int', 0),
                    'brands_universe' => $this->request->getPost('brands_universe', 'int', 0),
                    'price' => $this->request->getPost('price', 'int'),
                    'currency' => $this->request->getPost('currency', 'string'),
                    'hashtag' => $this->request->getPost('hashtag', 'name'),
                    'params' => $this->request->getPost('params', 'name'),
                    'active' => $this->request->getPost('active', 'bool'),
                ]);
                if($item->save())
                {
                    $images = $this->getDI()->get('images');
                    $picture = $images->add($this->request, 'products/'.$item->id);
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
            $item = Products::findFirstById($id);
            if(!$item)
            {
                return $this->notice('error', $this->local->_('not_found'), $this->initUrl());
            }
            $this->view->data = $item->toArray();
        }
        $this->view->langs = $this->initLangs();
        $this->view->categories = $this->getCategory();
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
            $sql = sprintf("update products set active='%s' where id in(%s)", $active, implode(',', (array) $ids));
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
        $result = $this->delete(Products::class, $id);
        $msg = $result
            ? $this->local->_('delete_ok')
            : $this->local->_('delete_error');
        $this->returnNotice($result, $msg, $this->initUrl());
    }

    private function getCategory()
    {
        $result = [];
        $categories = ['category','heroes','producer','brands','brands_universe'];
        $this->db->begin();
        foreach ($categories as $category)
        {
            $sql = null;
            switch ($category)
            {
                case "currency":
                    $sql = "select code, name from currency order by id";
                    break;
                case "category":
                    $sql = sprintf("select id, parent, nlevel(treepath) as level, coalesce(name->>'%s', name->>'en') as name from category order by treepath, parent nulls first ", $this->lang);
                    break;
                case "brands":
                case "brands_universe":
                    $sql = sprintf("with items as (select id, coalesce(name->>'%s', name->>'en') as name, articul, date_release from %s) select id, concat_ws(' ', name, articul, date_release) as name from items  order by id", $this->lang, $category);
                    break;
                case "producer":
                case "entity_type":
                case "heroes":
                default:
                    $sql = sprintf("select id, coalesce(name->>'%s', name->>'en') as name from %s order by id", $this->lang, $category);
                    break;
            }
            $result[$category] = $this->db->query($sql)->fetchAll(Enum::FETCH_ASSOC);
        }
        $this->db->commit();
        return $result;
    }

}