<?php

namespace App\Api\Controllers;

use App\Validation\FavoriteValidation;
use Phalcon\Db\Enum;

class FavouriteController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function listsAction()
    {
        $param = $this->getLimit();
        $sql = "select data.*, entity from favourites, jsonb_to_recordset(short_info(entity, entity_id, :lang)) as data(id bigint, name text, picture varchar(355)) where users @> quote_ident(:uid::text)::jsonb order by id";
        $query = new \Query();
        $items = $query->sql($sql, [
            'lang' => $this->lang,
            'uid' => $this->user,
        ])->paginate(
            $param->get('page'),
            $param->get('limit')
        );
        $this->resultOk($items);
    }

    public function addAction()
    {
        if($this->request->isPost())
        {
            $messages = $this->validData(new FavoriteValidation(), $this->request->getPost());
            if(count($messages))
            {
                $this->resultError($messages);
                return ;
            }
            $sql = "select favouritesadd(:entity, :entity_id, :uid)";
            $item = $this->db->query($sql, [
                'entity' => $this->request->getPost('entity'),
                'entity_id' => $this->request->getPost('entity_id'),
                'uid' => $this->user,
            ])->fetch(Enum::FETCH_ASSOC);
            if($item['favouritesadd'] === true)
            {
                $this->resultOk();
                return ;
            }
        }
        $this->resultError();
    }

    public function deleteAction()
    {
        if($this->request->isPost())
        {
            $messages = $this->validData(new FavoriteValidation(), $this->request->getPost());
            if(count($messages))
            {
                $this->resultError($messages);
                return ;
            }
            $sql = "select favouritesdelete(:entity, :entity_id, :uid)";
            $item = $this->db->query($sql, [
                'entity' => $this->request->getPost('entity'),
                'entity_id' => $this->request->getPost('entity_id'),
                'uid' => $this->user,
            ])->fetch(Enum::FETCH_ASSOC);
            if($item['favouritesdelete'] === true)
            {
                $this->resultOk();
                return ;
            }
        }
        $this->resultError();
    }

}