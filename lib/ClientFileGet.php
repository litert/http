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
        if (!isset($params[REQ_FIELD_URL])) {

            throw new Exception(<<<'ERROR'
Parameter "url" is required.
ERROR
            , E_LACK_FIELD_URL);
        }

        if (empty(AVAILABLE_METHODS[$method])) {

            throw new Exception(<<<ERROR
Method "{$method}" is not supported, please specify an upper-case method name.
ERROR
            , E_METHOD_UNSUPPORTED);
        }

        $reqOpts = [
            'http' => [
                'method' => $method,
                'ignore_errors' => true,
                'protocol_version' => 1.1
            ]
        ];

        $isHTTPS = substr($params[REQ_FIELD_URL], 0, 5) === 'https';

        if ($isHTTPS) {

            if ($params[REQ_FIELD_STRICT_SSL] ?? $this->strictSSL) {

                $reqOpts['ssl'] = [
                    'verify_peer' => true,
                    'verify_peer_name' => true
                ];

                if ($caFile = $params[REQ_FIELD_CA_FILE] ?? $this->caFile) {

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

        if (is_array($params[REQ_FIELD_HEADERS] ?? false)) {

            $params[REQ_FIELD_HEADERS] = array_combine(
                array_map(
                    'strtolower',
                    array_keys($params[REQ_FIELD_HEADERS])
                ),
                array_values($params[REQ_FIELD_HEADERS])
            );
        }
        else {

            $params[REQ_FIELD_HEADERS] = [];
        }

        if (METHOD_CONTENT_SUPPORTS[$method]) {

            /**
             * Only PATCH/POST/PUT methods supports (and requires) "data"
             * parameter.
             */
            if (empty($params[REQ_FIELD_DATA])) {

                throw new Exception(<<<ERROR
Parameter "data" is required for method "{$method}".
ERROR
                , E_LACK_FIELD_DATA);
            }

            if (is_array($params[REQ_FIELD_DATA])) {

                switch ($params[REQ_FIELD_DATA_TYPE] ?? 'form') {
                case 'json':
                    $dataType = JSON_CONTENT_TYPE;
                    $params[REQ_FIELD_DATA] = json_encode(
                        $params[REQ_FIELD_DATA],
                        JSON_UNESCAPED_UNICODE
                    );
                    break;
                case 'form':
                    $dataType = DEFAULT_DATA_CONTENT_TYPE;
                    $params[REQ_FIELD_DATA] = http_build_query(
                        $params[REQ_FIELD_DATA],
                        '',
                        '&',
                        PHP_QUERY_RFC3986
                    );
                    break;
                default:

                    throw new Exception(<<<ERROR
Unsupported type "{$params[REQ_FIELD_DATA_TYPE]}" of data.
ERROR
                    , E_INVALID_DATA_TYPE);
                }

                $params[REQ_FIELD_HEADERS]['content-type'] = $dataType;
            }

            $reqOpts['http']['content'] = $params[REQ_FIELD_DATA];
        }

        if ($params[REQ_FIELD_HEADERS]) {

            $reqOpts['http']['header'] = join(HTTP_EOL, array_map(
                function(string $k, $v) {
                    return "{$k}: $v";
                },
                array_keys($params[REQ_FIELD_HEADERS]),
                array_values($params[REQ_FIELD_HEADERS])
            ));
        }

        $response = new Response();

        $response->data = @file_get_contents(
            $params[REQ_FIELD_URL],
            false,
            stream_context_create($reqOpts)
        );

        if (!$response->data) {

            if (!$http_response_header) {

                throw new Exception(
                    'Failed to get response from server.',
                    E_REQUEST_FAILURE
                );
            }

            $response->data = '';
        }

        if ($params[REQ_FIELD_GET_HEADERS] ?? false) {

            $response->headers = HeaderParser::parseHeaderArray($http_response_header);
        }

        $response->code = intval(substr(
            $http_response_header[0],
            9,
            3
        ));

        return $response;
    }
}
