<?php

namespace App\Users\Controllers;

use App\Models\Users;

class ConfirmController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function initAction()
    {
        $this->resultOk();
    }

    public function checkAction(string $key)
    {
        $item = Users::findFirst([
            'conditions' => 'confirm_email=:key:',
            'bind' => [
                'key' => $this->filter->sanitize($key, ['string', 'striptags', 'trim'])
            ]
        ]);
        if($item)
        {
            if($item->banned === true)
            {
                $this->resultError('Banned', 403);
                return ;
            }
            $item->assign(['confirm_email'=>null]);
            $item->update();
            $this->resultOk();
            return;
        }
        $this->resultError('Not found', 404);
    }
}