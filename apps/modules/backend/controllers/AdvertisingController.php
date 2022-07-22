<?php

namespace App\Backend\Controllers;

class AdvertisingController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function viewAction()
    {}

    public function saveAction()
    {}

    public function manipulationAction(string $type, int $id = null)
    {
        if($type=='active')
        {}
        if($type=='hiden')
        {}
        $sql = "";
    }

    public function activeAction()
    {}

    public function hidenAction()
    {}

    public function deleteAction()
    {}

}