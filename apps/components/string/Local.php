<?php
use Phalcon\Http\Request;
use Phalcon\Session\{Manager as SessionManager,Adapter\Stream as SessionAdapter};
use Phalcon\Translate\Adapter\NativeArray;
use Phalcon\Translate\InterpolatorFactory;
use Phalcon\Translate\TranslateFactory;

class Local extends \Helper
{

    public static function getMenu($modules)
    {
        $messages = [];
        $lang = self::getLang();
        $file = self::getFile($modules, $lang, 'menu');
        if(file_exists($file) && is_file($file))
        {
            require $file;
        }
        return  $messages;
    }

    public static function getContent($modules) : NativeArray
    {
        $lang = self::getLang();
        $file = self::getFile($modules, $lang);
        return self::translate($file);
    }

    protected static function getLang()
    {
        if(!$lang = self::getSessionLang())
        {
            $request = new Request();
            $lang = $request->getBestLanguage();
        }
        return mb_substr($lang, 0, 2);
    }

    protected static function getSessionLang()
    {
        $session = new SessionManager();
        $stream = new SessionAdapter(['savePath' => sys_get_temp_dir()]);
        $session->setAdapter($stream);
        $session->start();
        return $session->get('lang') ?? false;
    }

    protected static function getFile($modules, $lang, ?string $menu = null)
    {
        $dir = self::getDir($modules, $menu);
        $file = $dir . $lang . '.php';
        if (true !== file_exists($file))
        {
            $file = $dir.'en.php';
        }
        return $file;
    }

    protected static function getDir($modules, ?string $menu = null)
    {
        $dir = '../apps/modules/'.$modules.'/messages/';
        if(!is_null($menu))
        {
            $dir .= $menu . DIRECTORY_SEPARATOR;
        }
        return $dir;
    }

    protected static function translate($file, $factorName = 'content')
    {
        $messages = [];
        if(file_exists($file) && is_file($file))
        {
            require $file;
        }
        $interpolator = new InterpolatorFactory();
        $factory      = new TranslateFactory($interpolator);

        return $factory->newInstance(
            'array',
            [
                $factorName => $messages,
            ]
        );
    }

}