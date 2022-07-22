<?php

class ParseMedia
{
    private $domain = null;
    public function __constructor($domain)
    {
        if(!\empty($domain))
        {
            $this->domain = $domain;
        }
    }

    public function __invoke()
    {
        if(!\is_null($this->domain))
        {
            foreach (new \ArrayIterator($this->match[$this->domain]) as $match)
            {
                //if(\preg_match($match, ))
            }
        }
    }

    private $match = [
        'rutub' => [
            '/[http|https]+:\/\/(?:www\.|)rutube\.ru\/video\/embed\/([a-zA-Z0-9_\-]+)/i',
            '/[http|https]+:\/\/(?:www\.|)rutube\.ru\/tracks\/([a-zA-Z0-9_\-]+)(&.+)?/i'
        ],
        'vimeo' => [
            '/[http|https]+:\/\/(?:www\.|)vimeo\.com\/([a-zA-Z0-9_\-]+)(&.+)?/i',
            '/[http|https]+:\/\/player\.vimeo\.com\/video\/([a-zA-Z0-9_\-]+)(&.+)?/i'
        ],
        'youtu' => [
            '/[http|https]+:\/\/(?:www\.|)youtube\.com\/watch\?(?:.*)?v=([a-zA-Z0-9_\-]+)/i',
            '/[http|https]+:\/\/(?:www\.|)youtube\.com\/embed\/([a-zA-Z0-9_\-]+)/i',
            '/[http|https]+:\/\/(?:www\.|)youtu\.be\/([a-zA-Z0-9_\-]+)/i'
        ]
    ];
}