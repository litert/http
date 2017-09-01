<?php
/*
   +----------------------------------------------------------------------+
   | LiteRT HTTP Library                                                  |
   +----------------------------------------------------------------------+
   | Copyright (c) 2007-2017 Fenying Studio                               |
   +----------------------------------------------------------------------+
   | This source file is subject to version 2.0 of the Apache license,    |
   | that is bundled with this package in the file LICENSE, and is        |
   | available through the world-wide-web at the following url:           |
   | https://github.com/litert/http/blob/master/LICENSE                   |
   +----------------------------------------------------------------------+
   | Authors: Angus Fenying <i.am.x.fenying@gmail.com>                    |
   +----------------------------------------------------------------------+
 */

declare (strict_types = 1);

namespace L\Http;

use L\Core\Exception;

class ClientCURL implements IClient
{
    const METHOD_MAPPING = [
        'GET' => CURLOPT_HTTPGET,
        'POST' => CURLOPT_POST,
        'DELETE' => CURLOPT_CUSTOMREQUEST,
        'PUT' => CURLOPT_CUSTOMREQUEST,
        'PATCH' => CURLOPT_CUSTOMREQUEST,
        'HEAD' => CURLOPT_CUSTOMREQUEST,
        'OPTIONS' => CURLOPT_CUSTOMREQUEST
    ];

    /**
     * @var string
     */
    public $caFile;

    /**
     * @var bool
     */
    public $strictSSL;

    public function __construct(array $config = [])
    {
        $this->strictSSL = true;

        foreach ($config as $key => $value) {

            $this->$key = $value;
        }
    }

    public function delete(array $params): Response
    {
        return $this->request('DELETE', $params);
    }

    public function get(array $params): Response
    {
        return $this->request('GET', $params);
    }

    public function head(array $params): Response
    {
        return $this->request('HEAD', $params);
    }

    public function options(array $params): Response
    {
        return $this->request('OPTIONS', $params);
    }

    public function patch(array $params): Response
    {
        return $this->request('PATCH', $params);
    }

    public function post(array $params): Response
    {
        return $this->request('POST', $params);
    }

    public function put(array $params): Response
    {
        return $this->request('PUT', $params);
    }

    public function request(string $method, array $params): Response
    {
        if (!isset($params['url'])) {

            throw new Exception(<<<'ERROR'
Parameter "url" is required.
ERROR
            );
        }

        if (empty(AVAILABLE_METHODS[$method])) {

            throw new Exception(<<<ERROR
Method "{$method}" is not supported, please specify an upper-case method name.
ERROR
            );
        }

        $ch = curl_init();

        $curlOpts = [
            CURLOPT_URL => $params['url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => intval($params['getHeaders'] ?? false),
            CURLOPT_NOBODY => intval(!($params['getData'] ?? true))
        ];

        $isHTTPS = substr($params['url'], 0, 5) === 'https';

        if ($isHTTPS && ($params['strictSSL'] ?? $this->strictSSL)) {

            $curlOpts[CURLOPT_SSL_VERIFYPEER] = true;
            $curlOpts[CURLOPT_SSL_VERIFYHOST] = 2;

            if ($caFile = $params['caFile'] ?? $this->caFile) {

                $curlOpts[CURLOPT_CAINFO] = $caFile;
            }
        }
        else {

            $curlOpts[CURLOPT_SSL_VERIFYPEER] = false;
            $curlOpts[CURLOPT_SSL_VERIFYHOST] = 0;
        }

        if ($params['headers'] ?? false) {

            $curlOpts[CURLOPT_HTTPHEADER] = array_map(
                function(string $k, $v) {
                    return "{$k}: {$v}";
                },
                array_keys($params['headers']),
                array_values($params['headers'])
            );
        }

        switch (self::METHOD_MAPPING[$method]) {
        case CURLOPT_POST:
            $curlOpts[CURLOPT_POST] = true;
            break;
        case CURLOPT_CUSTOMREQUEST:
            $curlOpts[CURLOPT_CUSTOMREQUEST] = $method;
            break;
        }

        if (METHOD_CONTENT_SUPPORTS[$method]) {

            /**
             * Only PATCH/POST/PUT methods supports (and requires) "data"
             * parameter.
             */
            if (empty($params['data'])) {

                throw new Exception(<<<ERROR
Parameter "data" is required for method "{$method}".
ERROR
                );
            }

            $curlOpts[CURLOPT_POSTFIELDS] = $params['data'];
        }
        else {

            /**
             * HEAD method returns no body but headers.
             *
             * CURL will be stuck if getData is set to TRUE.
             */
            if ($method === 'HEAD') {

                $curlOpts[CURLOPT_NOBODY] = 0;
            }
        }

        $data = [];

        curl_setopt_array($ch, $curlOpts);

        $data['data'] = curl_exec($ch);

        if ($data['data'] === false) {

            throw new Exception(curl_error($ch), curl_errno($ch));
        }

        if ($params['getHeaders'] ?? false) {

            $fullHeaderLength = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

            $data['headers'] = explode(HTTP_SEG_SEPARATOR, substr(
                $data['data'],
                0,
                $fullHeaderLength - 4
            ));

            $data['data'] = substr($data['data'], $fullHeaderLength);

            unset($fullHeaderLength);

            list(

                $data['headers'],
                $data['redirectionHeaders']

            ) = HeaderParser::parseCURLHeaders(
                $data['headers']
            );
        }

        if ($params['getProfile'] ?? false) {

            $info = curl_getinfo($ch);
            $data['code'] = $info['http_code'];
            $data['profile'] = $info;
        }
        else {

            $data['code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        }

        curl_close($ch);

        return new Response($data);
    }
}