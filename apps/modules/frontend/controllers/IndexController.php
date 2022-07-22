<?php
namespace App\Frontend\Controllers;

use App\Models\Guest;
use App\Models\Roles;
use App\Models\Users;
use Phalcon\Db\Enum;
use Phalcon\Validation;
use Phalcon\Validation\Validator\File as FileValidator;
use Phalcon\Validation\Validator\Callback as CallbackValidator;

class IndexController extends ControllerBase
{
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

    public function indexAction()
    {
        $this->resultOk();
    }

    public function Curl($url = '') {
        if (empty($url)) {return false;}

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);

        $data = curl_exec($ch);
        return $data;
    }
}