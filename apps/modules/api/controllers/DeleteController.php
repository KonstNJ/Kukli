<?php

namespace App\Api\Controllers;

class DeleteController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function pictureAction()
    {
        if($this->request->hasPost('picture'))
        {
            $picture = $this->request->getPost('picture');
            if($this->permit($picture))
            {
                $dir = $this->getDI()->get('config')->dir;
                $file = $dir . $picture;
                if(file_exists($file) && is_file($file))
                {
                    if(unlink($file))
                    {
                        $this->resultOk();
                        return ;
                    }
                }
                $this->resultError('Not Found', 404);
                return ;
            }
            $this->resultError('Forbidden', 403);
            return ;
        }
        $this->resultError();
    }

    public function modelAction()
    {}

    private function permit(string $path)
    {
        $dir = $this->getDI()->get('config')->images->dir;
        $sub_path = $dir . $this->user . '/';
        $strpos = \mb_stripos($path, $sub_path);
        return ($strpos === false) ? false : true;
    }

}