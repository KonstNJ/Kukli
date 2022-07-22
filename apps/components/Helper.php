<?php

class Helper extends \Phalcon\Di\Injectable
{
    /**
     * @return string
     */
    public function getHost()
    {
        $request = $this->getDI()->get('request');
        return $request->getScheme() . '://' . $request->getHttpHost();
    }

    /**
     * @param string $link
     * @return false|string
     */
    public function brokenLink(string $link)
    {
        if(\filter_var($link, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED))
        {
            $header = \get_headers($link, true);
            if(\mb_substr($header[0], 9, 3) !== '404')
            {
                return $link;
            }
        }
        return false;
    }

    /**
     * @param $route
     * @return string
     */
    public function getRoute($route)
    {
        return $this->router->getRouteByName($route)->getPattern();
    }

    /**
     * @param array $data
     * @return array
     */
    public function recursiveLists(array $data)
    {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($data));
        $data = $this->lists($iterator);
        return $this->enumeration($data);
    }

    /**
     * @param array|iterable $data
     * @return Generator
     */
    public function lists($data)
    {
        foreach ($data as $item)
        {
            yield $item;
        }
    }

    /**
     * @param iterable $data
     * @return array
     */
    public function enumeration(iterable $data) : array
    {
        $array = [];
        foreach ($data as $item)
        {
            $array[] = $item;
        }
        return $array;
    }
}