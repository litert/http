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

class ClientFileGet implements IClient
{
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

        $reqOpts = [
            'http' => [
                'method' => $method
            ]
        ];

        $isHTTPS = substr($params['url'], 0, 5) === 'https';

        if ($isHTTPS) {

            if ($params['strictSSL'] ?? $this->strictSSL) {

                $reqOpts['ssl'] = [
                    'verify_peer' => true,
                    'verify_peer_name' => true
                ];

                if ($caFile = $params['caFile'] ?? $this->caFile) {

                    $reqOpts['ssl']['cafile'] = $caFile;
                }
            }
            else {

                $reqOpts['ssl'] = [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ];
            }
        }

        if ($params['headers'] ?? false) {

            $reqOpts['http']['header'] = join(HTTP_EOL, array_map(
                function(string $k, $v) {
                    return "{$k}: {$v}";
                },
                array_keys($params['headers']),
                array_values($params['headers'])
            ));
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

            $reqOpts['http']['content'] = $params['data'];
        }

        $data = [];

        $data['data'] = @file_get_contents(
            $params['url'],
            false,
            stream_context_create($reqOpts)
        );

        if ($data['data'] === false) {

            throw new Exception("Failed to read.");
        }

        if ($params['getHeaders'] ?? false) {

            $data['headers'] = HeaderParser::parseHeaderArray($http_response_header);
        }

        $data['code'] = 200;

        return new Response($data);
    }
}
