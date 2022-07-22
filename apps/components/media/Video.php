<?php

namespace components\media;

class Video extends \Helper
{
    public function init($videos)
    {
        $result = [];
        if(!\empty($videos))
        {
            foreach (new \ArrayIterator((array) $videos) as $video)
            {
                if($url = $this->checkLink($video))
                {
                    if($video_link = $this->getLink($url))
                    {
                        $result[] = $video_link;
                    }
                }
            }
        }
        return $result;
    }

    private function getLink($link)
    {
        $host = \parse_url($link, PHP_URL_HOST);
        $host = \str_replace('www.', '', $host);
        $domain_name = \mb_substr($host, 0, 5);
        if(\in_array($domain_name, ['rutub', 'vimeo', 'youtu', 'sprou']))
        {
            return $link;
        }
        return false;
    }

    private function checkLink($link)
    {
        if(!\preg_match('/^(http|https)\:\/\//i', $link))
        {
            $link = 'https://' . $link;
        }
        return self::brokenLink($link);
    }

    private function getMedia($url, $domain)
    {
        if(!\empty($domain[$domain]))
        {
            $result = [];
            foreach (new \ArrayIterator($domain[$domain]) as $match)
            {
                if(preg_match($match, $url, $matches))
                {

                    break;
                }
            }
        }
        return false;
    }



    private function getCurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);

        $data = curl_exec($ch);
        return $data;
    }


}