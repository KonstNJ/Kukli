<?php

namespace App\Backend\Controllers;

use App\Models\Pages;
use GuzzleHttp\Cookie\CookieJar;
use Phalcon\Db\Enum;

class PagesController extends ControllerBase
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
        $sort_param = (new \Sorts($sort_default, 'id', 'asc'))
            ->query($params)
            ->get();
        if(!empty($sort_param['s']))
        {
            $sql = "select id, parent, nlevel(treepath) as level, langOrFirst(content, :lang, 'ru', 'name') as name, getFirstImages(picture) as picture, active, date_create from pages, plainto_tsquery(:search) as search_query where search_tsv @@ search_query order by ts_rank(search_tsv, search_query) desc";
            $items = (new \Query())->sql($sql, [
                'lang' => $this->lang,
                'search'=>$sort_param['s'],
            ])->paginate($sort_param['current'], $sort_param['limit']);
        } else {
            $sql = "select id, parent, nlevel(treepath) as level, langOrFirst(content, :lang, 'ru', 'name') as name, getFirstImages(picture) as picture, active, date_create from pages order by ".$sort_param['order'];
            $items = (new \Query())->sql($sql, ['lang'=>$this->lang])
                ->paginate($sort_param['current'], $sort_param['limit']);
        }
        $this->view->search_param = $sort_param['s'] ?? null;
        $this->view->data = $items;
        $this->view->sorts = $this->sortDesc(array_keys($sort_default));
    }

    public function saveAction(?int $id = null)
    {
        if($this->request->getPost())
        {
            $msg = 'save_error';
            $status = false;
            $id = $this->request->getPost('id', 'int');
            $item = \ModelControl::init(Pages::class, $id);
            if($item)
            {
                $modules = [];
                if($this->request->hasPost('modules'))
                {
                    $modules = $this->pareseModules( $this->request->getPost('modules'));
                }

                $item->assign([
                    'parent' => $this->request->getPost('parent', 'int'),
                    'positions' => $this->request->getPost('positions', 'int'),
                    'menu' => $this->request->getPost('menu', 'name', 'top'),
                    'url' => $this->request->getPost('name', 'translate'),
                    'content' => $this->request->getPost('content'),
                    'modules' => $modules,
                    'active' => $this->request->getPost('active')
                ]);
                if($item->save())
                {
                    $images = $this->getDI()->get('images');
                    $picture = $images->add($this->request, 'pages/'.$item->id.'/');
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
            $item = Pages::findFirstById($id);
            if(!$item)
            {
                return $this->notice('error', $this->local->_('not_found'), $this->initUrl());
            }
            $this->view->data = $item->toArray();
        }
        $sql = "select id, parent, nlevel(treepath) as level, menu, coalesce(((content->>'%s')::jsonb)->>'name', ((content->>'en')::jsonb)->>'name') as name from pages order by treepath, parent nulls first";
        $sql = sprintf($sql, $this->lang);
        $this->view->langs = $this->initLangs();
        $this->view->lists = $this->db
            ->query($sql)
            ->fetchAll(Enum::FETCH_ASSOC);
    }

    /**
     * @param string $type
     * @param int|null $id
     * @return void
     */
    public function manipulationAction(string $type, int $id = null)
    {
        $msg = 'update_error';
        $status = false;
        if($ids = $this->getId($id))
        {
            $active = ($type=='hiden') ? 'f' : 't';
            $sql = sprintf("update pages set active='%s' where id in(%s)", $active, implode(',', (array) $ids));
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
    public function deleteDelete(?int $id = null)
    {
        $result = $this->delete(Pages::class, $id);
        $msg = $result
            ? $this->local->_('delete_ok')
            : $this->local->_('delete_error');
        $this->returnNotice($result, $msg, $this->initUrl());
    }

    /**
     * @param array $modules
     * @return array
     */
    private function pareseModules(array $modules) : array
    {
        $result = [];
        foreach (new \ArrayIterator($modules) as $item)
        {
            if(empty($item['title']))
            {
                unset($item['title']);
            } else {
                $item['title'] = ['default'=>$item['title']];
            }
            $result[] = $item;
        }
        return $result;
    }

}