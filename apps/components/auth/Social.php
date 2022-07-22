<?php

class Social extends \Helper
{
    /**
     * @param string $service
     * @return \Hybridauth\Adapter\AdapterInterface|null
     */
    public function init(string $service) : \Hybridauth\Adapter\AdapterInterface
    {
        $service = \mb_strtolower($service);
        $social = $this->getDI()->get('config')->social;
        if(!$social->has($service))
        {
            return false;
        }
        $item = $social->get($service);
        $config = [
            'callback' => $this->getUrl($service),
            'keys' => [
                'id' => $item->app_id,
                'secret' => $item->app_secret,
                'key' => $item->app_key
            ]
        ];
        $service_class = '\Hybridauth\Provider\\'.\ucfirst($item->provider);
        if(\class_exists($service_class))
        {
            try {
                $adapter = new $service_class($config);
                if (!$adapter->isConnected()) {
                    $adapter->authenticate();
                }
                return $adapter;
            } catch (\Exception $exception)
            {
                \error_log($exception->getMessage() . PHP_EOL . $exception->getTraceAsString());
                return false;
            }
        }
        return false;
    }

    /**
     * @return array
     */
    public function getAutUrl()
    {
        $result = [];
        $socials = $this->getDI()->get('config')->social;
        foreach (new \ArrayIterator(\array_keys($socials->toArray())) as $social)
        {
            $social = \mb_strtolower($social);
            $result[$social] = $this->getUrl($social);
        }
        return $result;
    }

    /**
     * @param $service
     * @return string
     */
    public function getUrl($service)
    {
        $domain = $this->getHost();
        $signup = $this->router->getRouteByName('users-signup')->getPattern();
        return $domain . $signup . '/' . $service;
    }
}