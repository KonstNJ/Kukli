<?php
namespace App\Models\Traits;
use Phalcon\Di;
use Phalcon\Http\Request;
use Phalcon\Di\DiInterface;

trait ExtendModel
{
    /**
     * @param string $model
     * @param int $item
     * @return void
     */
    public function removeDir(string $model, int $item)
    {
        $dir = Di::getDefault()->get('config')->dir;
        $dir .= '/data/'.$model.'/'.$item;
        $this->removePathDir($dir);
    }

    /**
     * @param string $sub_path
     * @return void
     */
    public function removeUsersDir(string $sub_path)
    {
        $dir = Di::getDefault()->get('config')->dir;
        $dir .= '/data/' . $sub_path;
        $this->removePathDir($dir);
    }

	public function removeImages(array $images)
	{
		$expand = function($data) {
			$iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($data));
			foreach ($iterator as $item)
			{
				yield $item;
			}
			return true;
		};
        $dir = Di::getDefault()->get('config')->dir;
        $file_ext =  new \ArrayIterator(['.webp', '.jpg', '@2.jpg', '@2.webp']);

		foreach ($expand($images) as $image)
		{
            $file = $dir . $image;
			if(file_exists($file) && is_file($file))
			{
                unlink($file);
			} else {
                foreach ($file_ext as $ext)
                {
                    $_file = $file . $ext;
                    if(file_exists($_file) && is_file($_file))
                    {
                        unlink($file);
                    }
                }
            }
		}
	}

    /**
     * @param $el
     * @return false|mixed|string
     */
    public function Jsonb($el)
    {
        if(!empty($el))
        {
            if(is_string($el))
            {
                return $el;
            }
            if(is_array($el) || is_object($el))
            {
                return json_encode($el, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
            }
        }
        return null;
    }

    /**
     * @param string $val
     * @return array|string|string[]|null
     */
    public function JsonInt(string $val)
    {
        if(is_null($val))
        {
            return null;
        }
        return preg_replace(
            '/(\d+)/',
            '"$1"',
            str_replace('"','', $val)
        );
    }

    /**
     * @param $content
     * @return array|mixed
     */
    public function parseLangsContent($content)
    {
        if(is_array($content))
        {
            $result = [];
            $content_key = ['name','anons','about','title','description','keywords','content'];
            foreach (new \ArrayIterator($content) as $lang => $data)
            {
                foreach (new \ArrayIterator($content_key) as $key)
                {
                    if(!empty($data[$key]))
                    {
                        $result[$lang][$key] = $data[$key];
                    }
                }
            }
            return $result;
        }
        return $content;
    }

    /**
     * @return array|false|string|string[]|null
     */
    private function getClass()
    {
        $class = get_class();
        return mb_strtolower(mb_substr($class, mb_strrpos($class, '\\')+1));
    }

    /**
     * @param $dir
     * @return void
     */
    private function removePathDir($dir)
    {
        if(file_exists($dir) && is_dir($dir))
        {
            $iteratorDir = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
            $dirFiles = new \RecursiveIteratorIterator($iteratorDir, \RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($dirFiles as $file)
            {
                if($file->isDir())
                {
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
            rmdir($dir);
            //exec("rm -rf -d {$dir} > /dev/null &");
        }
    }

}
