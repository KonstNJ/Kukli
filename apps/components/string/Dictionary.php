<?php
use Phalcon\Translate\Adapter\NativeArray;
use Phalcon\Translate\InterpolatorFactory;
use Phalcon\Translate\TranslateFactory;

class Dictionary extends \Helper
{
    public function get(string $lang = null)
    {
        $messages = [];
        if($file = $this->getFile($lang))
        {
            require $file;
        }
        $interpolator = new InterpolatorFactory();
        $factory      = new TranslateFactory($interpolator);

        return $factory->newInstance(
            'array',
            [
                'content' => $messages,
            ]
        );
    }

    private function getFile(string $lang = null)
    {
        if(!is_null($lang))
        {
            $file = $this->getDir() . $lang . '.php';
            if(file_exists($file) && is_file($file))
            {
                return $file;
            }
        }
        return $this->getDir() . 'default.php';
    }

    private function getDir()
    {
        return dirname(__DIR__, 2) . '/config/i18n/';
    }

}