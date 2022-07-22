<?php

class Resp extends \Helper
{
    public function getJson($data, int $code = 200)
    {
        $request = $this->getDI()->get('request');
        $response = $this->getDI()->get('response');
        $origin = $request->getHeader("ORIGIN")
            ? $request->getHeader("ORIGIN")
            : '*';
        $response->setHeader('Access-Control-Allow-Origin', $origin)
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE')
            ->setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Range, Content-Disposition, Content-Type, Authorization')
            ->setHeader('Access-Control-Max-Age', 86400);
        if ($origin !== '*') {
            $response->setHeader('Access-Control-Allow-Credentials', 'true');
        }
        $persistent = $this->getDI()->getShared('sessionBag');
        $data = array_merge([
            'uid'=>$persistent->get('token'),
            'lang' => $persistent->has('lang')
                ? $persistent->get('lang')
                : 'en',
        ], (array) $data);
        $response->sendHeaders()
            ->setContentType('application/json')
            ->setStatusCode($code, static::getCode($code))
            ->setJsonContent($data);
        return $response->send();
    }

    public function getCode(int $code): string
    {
        $status = [
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Time-out',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Large',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Time-out',
            505 => 'Version Not Supported',
        ];
        return array_key_exists($code, $status)
            ? $status[$code]
            : 'Bad Request';
    }


}