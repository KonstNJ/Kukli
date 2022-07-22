<?php

use Phalcon\Http\Request;
use Phalcon\Image\Adapter\{Imagick,Gd};
use Phalcon\Image\ImageFactory;
use Phalcon\Di;

class Images extends \Helper
{
    public static function getList(?string $picture = null) : array
    {
        return (!\is_null($picture)) ? \json_decode($picture, true) : [];
    }

	public function add(Request $request, string $mod_dir, bool $copyes = true)
	{
		$img_arr = [];
		if($request->hasFiles())
		{
			$conf = $this->getDI()->get('config');
			$full_dir = $conf->dir;
			$img_dir = $conf->images->dir . $mod_dir . DIRECTORY_SEPARATOR;
			if(\Dir::initDir($full_dir . $img_dir))
			{
				$img_max_h = $conf->images->size->max->h;
				$img_max_w = $conf->images->size->max->w;
				$img_min_h = $conf->images->size->min->h;
				$img_min_w = $conf->images->size->min->w;
                $files = $this->lists($request->getUploadedFiles());
                $string = Di::getDefault()->getShared('string');
				foreach ($files as $file)
				{
                    try {
                        if($image = new Imagick($file->getTempName()))
                        {
                            $file_name = $string::gen();
                            if(\mb_strtolower($image->getMime())=='image/gif')
                            {
                                $image->save($full_dir . $img_dir . $file_name . '.jpg');
                            } else {
                                if($image->getHeight() < $img_max_h)
                                {
                                    $img_max_h = $image->getHeight();
                                }
                                if($image->getWidth() < $img_max_w)
                                {
                                    $img_max_w = $image->getWidth();
                                }
                                if($image->getHeight() < $img_min_h)
                                {
                                    $img_min_h = $image->getHeight();
                                }
                                if($image->getWidth() < $img_min_w)
                                {
                                    $img_min_w = $image->getWidth();
                                }
                                if($copyes)
                                {
                                    $image->resize($img_max_w, $img_max_h)
                                        ->save($full_dir . $img_dir . $file_name . '@2.webp')
                                        ->save($full_dir . $img_dir . $file_name . '@2.jpg')
                                        ->resize($img_min_w, $img_min_h)
                                        ->save($full_dir . $img_dir . $file_name . '.webp')
                                        ->save($full_dir . $img_dir . $file_name . '.jpg');
                                } else {
                                    $image->resize($img_min_w, $img_min_h)
                                        ->save($full_dir . $img_dir . $file_name . '.webp')
                                        ->save($full_dir . $img_dir . $file_name . '.jpg');
                                }
                            }
                            $img_arr[] = $img_dir . $file_name;

                        }
                    } catch (\Exception $e) {
                        \error_log($e->getMessage() . PHP_EOL . $e->getTraceAsString());
                        continue;
                    }
                    \unlink($file->getTempName());
				}
			}
		}
		return $img_arr;
	}

    /**
     * @param string $img
     * @param int $users_id
     * @return array|false
     */
    public function social(string $img, int $users_id)
    {
        if($link = self::brokenLink($img))
        {
            $temp_file_name = \tempnam(\sys_get_temp_dir(), 'users_avatar');
            $file_content = \file_get_contents($link);
            if(\file_put_contents($temp_file_name, $file_content))
            {
                $conf = $this->getDI()->get('config');
                $full_dir = $conf->dir;
                $img_dir = $conf->images->dir . $users_id . '/users/';
                if(\Dir::initDir($full_dir . $img_dir))
                {
                    if($image = new Imagick($temp_file_name))
                    {
                        $file_name = Di::getDefault()->getShared('string')::gen();
                        $img_min_h = $conf->images->size->min->h;
                        $img_min_w = $conf->images->size->min->w;
                        if($image->getHeight() < $img_min_h)
                        {
                            $img_min_h = $image->getHeight();
                        }
                        if($image->getWidth() < $img_min_w)
                        {
                            $img_min_w = $image->getWidth();
                        }
                        $image->resize($img_min_w, $img_min_h)
                            ->save($full_dir . $img_dir . $file_name . '.webp')
                            ->save($full_dir . $img_dir . $file_name . '.jpg');
                        $img_arr[] = $img_dir . $file_name;
                        return $img_arr;
                    }
                }
            }
            if(\file_exists($temp_file_name))
            {
                \unlink($temp_file_name);
            }
        }
        return false;
    }

    public function imagesDelete(?array $images = [])
    {
        if(!empty($images))
        {
            $dir = $this->getDI()->get('config')->dir;
            $ext = new \ArrayIterator(['.jpg', '.webp', '@2.jpg', '@2.webp']);
            foreach ($this->lists($images) as $image)
            {
                $file = $dir . $image;
                foreach ($ext as $file_ext)
                {
                    $file .= $file_ext;
                    if(\file_exists($file) && is_file($file))
                    {
                        \unlink($file);
                    }
                    echo $file.'<br />';
                }
            }
        }
    }

	public function imagesDiffDel(array $old_images, array $new_images)
	{
        $old = $this->recursiveLists($old_images);
		$new = $this->recursiveLists($new_images);
        if(!empty($new)) {
            $images = \array_diff($old, $new);
        } else {
            $images = $old;
        }
        $this->imagesDelete($images);
		return '';
	}

}
