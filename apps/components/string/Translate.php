<?php

class Translate extends Helper
{
    private $url;
    private $text_limit;

    public function __construct()
    {
        $this->url = 'https://translate.googleapis.com/translate_a/single?client=gtx&dt=t';
        $this->text_limit = 1000;
    }

    public function test(string $source_lang, string $target_lang, string $text)
    {
        if($this->session->has('translate'))
        {
            var_dump('session');
            $tmp = $this->session->get('translate');
            $parse_str = $this->pareStr($tmp);
            var_dump($parse_str);
            return ;
        } else {
            $tmp = $this->init($source_lang, $target_lang, $text);
            $this->session->set('translate', $tmp);
        }
    }

    public function init(string $source_lang, string $target_lang, string $text)
    {
        $texts = $this->preparation($text);
        $tmp = [];
        foreach (new \ArrayIterator($texts) as $item)
        {
            $tmp[] = $this->request($item, $source_lang, $target_lang);
            //sleep(1);
        }
        $result = $this->pareStr($tmp);
        return $result;
    }

    public function pareStr($request)
    {
        $result = '';
        if(!empty($request))
        {
            foreach ($request as $text)
            {
                $t = json_decode($text, true);
                $result .= isset($t[0]) ? urldecode($t[0][0][0]) : '';
            }
        }
        return $result;
    }

    public function request(string $text, string $source_lang, string $target_lang)
    {
        $param = [
            'sl' => urlencode($source_lang),
            'tl' => urlencode($target_lang),
            'q' => urlencode($text)
        ];
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, count($param));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    private function preparation($text)
    {
        $result = [];
        if(mb_strlen($text) > $this->text_limit)
        {
            $reset_index = 0;
            $temp_text = [];
            $simbol_sum = 0;
            foreach (new \ArrayIterator(explode(' ', $text)) as $str)
            {
                $str_len = mb_strlen($str);
                if(($simbol_sum + $str_len) > $this->text_limit)
                {
                    $simbol_sum = 0;
                    $reset_index++;
                }
                $simbol_sum += $str_len;
                $temp_text[$reset_index][] = $str;
            }
            foreach (new \ArrayIterator($temp_text) as $str)
            {
                $result[] = implode(' ', $str);
            }
        } else {
            $result[] = $text;
        }
        return $result;
    }
}