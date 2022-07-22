<?php
use Phalcon\Security\Random;
class Str extends \Helper
{
    public static function uuid()
    {
        return (new Random())->uuid();
    }

    public static function getNum(int $length = 5)
    {
        return (new Random())->bytes($length);
    }

    public static function gen(int $length = 20, bool $spec = false) : string
    {
        $random = new Random();
        return (true == $spec)
            ? $random->base64($length)
            : $random->base64Safe($length);
    }

    public static function generator(int $length=15, bool $key=true) {
        $result = '';
        if($key===true) {
            $s = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        } else {
            $s = '-_~!@#$%^&*()_+\/|1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($s) - 1);
            $result .= $s[$index];
        }
        return $result;
    }

    public static function differencePercentages(int $filrs, int $second)
    {
        return round((($filrs - $second) * 100) / $filrs, 2);
    }

    public static function numberFormat(int $n, $precision = 3) {
        if ($n < 1000) {
            $result = number_format($n);
        } else if ($n > 1000) {
            $result = number_format($n / 1000, $precision) . 'K';
        } else if ($n < 1000000000) {
            $result = number_format($n / 1000000, $precision) . 'M';
        } else {
            $result = number_format($n / 1000000000, $precision) . 'B';
        }
        return $result;
    }

    public static function arrawStat($data)
    {
        $numers = json_decode($data, true);
        $first = intval($numers[0]);
        $second = intval($numers[1]);

        $num = ($second > 0 && $first > 0) ? self::differencePercentages($first, $second) : 0;
        $icon = ($num === abs($num)) ? 'cil-arrow-top' : 'cil-arrow-bottom';
        $html = '<span class="fs-6 fw-normal">('.$num.'%
                                    <svg class="icon">
                                    <use xlink:href="/assets/icons/sprites/free.svg#'.$icon.'"></use>
                                    </svg>)
                                </span>';
        return $html;
    }

    public static function nl2brReverse(?string $string = '') : string
    {
        return str_replace('<br />', "\r\n", $string);
    }

    public static function likesSeparator(string $str)
    {
        if(preg_match('/\((.+?)\)/', $str, $match))
        {
            list($likes,$response_type) = explode(',', $match[1]);
            return ['likes'=>$likes,"response_type"=>$response_type];
        }
        return false;
    }

    public static function normalize(string $str, $sep = null)
    {
        $s = trim(preg_replace('/((?>[^\p{L}0-9]+)|( {2,}))/imu', ' ', $str));
        return (!is_null($sep)) ? str_replace(' ', $sep, $s) : $s;
    }

    public static function translate(string $text) : string
    {
        $str = str_replace(
            " ",
            "-",
            preg_replace('/[^\p{L}0-9 ]/iu','', trim($text))
        );
        $iso = [
            "Є"=>"YE","І"=>"I","Ѓ"=>"G","і"=>"i","№"=>"num","є"=>"ye","ѓ"=>"g",
            "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D","Е"=>"E","Ё"=>"YO","Ж"=>"ZH",
            "З"=>"Z","И"=>"I","Й"=>"J","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R",
            "С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"X","Ц"=>"C","Ч"=>"CH","Ш"=>"SH","Щ"=>"SHH","Ъ"=>"'",
            "Ы"=>"Y","Ь"=>"","Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
            "е"=>"e","ё"=>"yo","ж"=>"zh","з"=>"z","и"=>"i","й"=>"j","к"=>"k","л"=>"l","м"=>"m","н"=>"n",
            "о"=>"o","п"=>"p","р"=>"r","с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"x","ц"=>"c",
            "ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"","ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
            "À"=>"A","Á"=>"A","Â"=>"A","Ã"=>"A","Å"=>"A","Ä"=>"A","Æ"=>"AE",
            "à"=>"a","á"=>"a","â"=>"a","ã"=>"a","å"=>"a","ä"=>"a","æ"=>"ae",
            "Þ"=>"B","þ"=>"b","Č"=>"C","Ć"=>"C","Ç"=>"C","č"=>"c","ć"=>"c",
            "ç"=>"c","Ď"=>"D","ð"=>"d","ď"=>"d","Đ"=>"Dj","đ"=>"dj","È"=>"E",
            "É"=>"E","Ê"=>"E","Ë"=>"E","è"=>"e","é"=>"e","ê"=>"e","ë"=>"e",
            "Ì"=>"I","Í"=>"I","Î"=>"I","Ï"=>"I","ì"=>"i","í"=>"i","î"=>"i",
            "ï"=>"i","Ľ"=>"L","ľ"=>"l","Ñ"=>"N","Ň"=>"N","ñ"=>"n","ň"=>"n",
            "Ò"=>"O","Ó"=>"O","Ô"=>"O","Õ"=>"O","Ø"=>"O","Ö"=>"O","Œ"=>"OE",
            "ð"=>"o","ò"=>"o","ó"=>"o","ô"=>"o","õ"=>"o","ö"=>"o","œ"=>"oe",
            "ø"=>"o","Ŕ"=>"R","Ř"=>"R","ŕ"=>"r","ř"=>"r","Š"=>"S","š"=>"s",
            "ß"=>"ss","Ť"=>"T","ť"=>"t","Ù"=>"U","Ú"=>"U","Û"=>"U","Ü"=>"U",
            "Ů"=>"U","ù"=>"u","ú"=>"u","û"=>"u","ü"=>"u","ů"=>"u","Ý"=>"Y",
            "Ÿ"=>"Y","ý"=>"y","ý"=>"y","ÿ"=>"y","Ž"=>"Z","ž"=>"z"
        ];
        $result_str = '';
        foreach (mb_str_split($str) as $symbol)
        {
            $result_str .= (array_key_exists($symbol, $iso))
                ? $iso[$symbol]
                : $symbol;
        }
        return  $result_str;
    }
}