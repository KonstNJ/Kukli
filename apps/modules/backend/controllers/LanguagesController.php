<?php

namespace App\Backend\Controllers;

use App\Models\Languages;
use App\Validation\LanguagesValidation;

class LanguagesController extends ControllerBase
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
            'code' => 'code',
        ];
        $params = $this->getDI()->get('params')->get(true);
        $sort_param = (new \Sorts($sort_default, 'id', 'asc'))
            ->query($params)
            ->get();
        if(!empty($sort_param['s']))
        {
            $items = (new \Query())->sql('select id, name, code, defaults from languages where name % :search order by similarity(name, :search) desc, '.$sort_param['order'], ['search'=>$sort_param['s']])
                ->paginate($sort_param['current'], $sort_param['limit']);
        } else {
            $items = (new \Query())->sql('select id, name, code, defaults from languages order by '.$sort_param['order'])
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
            $messages = $this->validData(new LanguagesValidation(), $this->request->getPost());
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
            $item = \ModelControl::init(Languages::class, $id);
            if($item)
            {
                $item->assign([
                    'name' => $this->request->getPost('name'),
                    'code' => $this->request->getPost('code'),
                    //'dictionary' => $this->request->getPost('dictionary'),
                    'defaults' => $this->request->getPost('defaults', 'int', 0)
                ]);
                if($item->save())
                {
                    $msg = 'save_ok';
                    $status = true;
                }
            }
            $this->returnNotice($status, $this->local->_($msg), $this->initUrl());
            return ;
        }
        if(!is_null($id))
        {
            $item = Languages::findFirstById($id);
            if(!$item)
            {
                return $this->notice('error', $this->local->_('not_found'), $this->initUrl());
            }
            $this->view->data = $item->toArray();
        }
    }

    public function deleteAction(?int $id = null)
    {
        $result = $this->delete(Languages::class, $id);
        $msg = $result
            ? $this->local->_('delete_ok')
            : $this->local->_('delete_error');
        $this->returnNotice($result, $msg, $this->initUrl());
    }
}