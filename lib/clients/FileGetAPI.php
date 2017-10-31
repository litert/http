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

namespace L\Http\Client;

use L\Http as http, L\Http\Errors as errors;

class FileGetAPI extends AbstractClient
{
    public function request(string $method, array $params): Response
    {
        if (!isset($params[http\REQ_FIELD_URL])) {

            throw new Exception(<<<'ERROR'
Parameter "url" is required.
ERROR
            , errors\C_LACK_FIELD_URL);
        }

        if (empty(http\CLIENT_AVAILABLE_METHODS[$method])) {

            throw new Exception(<<<ERROR
Method "{$method}" is not supported, please specify an upper-case method name.
ERROR
            , errors\C_METHOD_UNSUPPORTED);
        }

        $timeout = $params[http\REQ_FIELD_TIMEOUT] ?? $this->timeout;

        $reqOpts = [
            'http' => [
                'method' => $method,
                'ignore_errors' => true,
                'timeout' => $timeout
            ]
        ];

        switch ($version = $params[http\REQ_FIELD_VERSION] ?? $this->version) {
        case 1.0:
        case 1.1:
            $reqOpts['http']['protocol_version'] = $version;
            break;
        default:

            throw new Exception(<<<ERROR
Version "{$version}" of HTTP protocol is not supported yet.
ERROR
            , errors\C_VERSION_UNSUPPORTED);
        }

        $isHTTPS = substr($params[http\REQ_FIELD_URL], 0, 5) === 'https';

        if ($isHTTPS) {

            if ($params[http\REQ_FIELD_STRICT_SSL] ?? $this->strictSSL) {

                $reqOpts['ssl'] = [
                    'verify_peer' => true,
                    'verify_peer_name' => true
                ];

                if ($caFile = $params[http\REQ_FIELD_CA_FILE] ?? $this->caFile) {

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

        if (is_array($params[http\REQ_FIELD_HEADERS] ?? false)) {

            $params[http\REQ_FIELD_HEADERS] = array_combine(
                array_map(
                    'strtolower',
                    array_keys($params[http\REQ_FIELD_HEADERS])
                ),
                array_values($params[http\REQ_FIELD_HEADERS])
            );
        }
        else {

            $params[http\REQ_FIELD_HEADERS] = [];
        }

        if (http\METHOD_CONTENT_SUPPORTS[$method]) {

            /**
             * Only PATCH/POST/PUT methods supports (and requires) "data"
             * parameter.
             */
            if (empty($params[http\REQ_FIELD_DATA])) {

                throw new Exception(<<<ERROR
Parameter "data" is required for method "{$method}".
ERROR
                , errors\C_LACK_FIELD_DATA);
            }

            if (is_array($params[http\REQ_FIELD_DATA])) {

                switch ($params[http\REQ_FIELD_DATA_TYPE] ?? 'form') {
                case 'json':
                    $dataType = http\JSON_CONTENT_TYPE;
                    $params[http\REQ_FIELD_DATA] = json_encode(
                        $params[http\REQ_FIELD_DATA],
                        JSON_UNESCAPED_UNICODE
                    );
                    break;
                case 'form':
                    $dataType = http\DEFAULT_DATA_CONTENT_TYPE;
                    $params[http\REQ_FIELD_DATA] = http_build_query(
                        $params[http\REQ_FIELD_DATA],
                        '',
                        '&',
                        PHP_QUERY_RFC3986
                    );
                    break;
                default:

                    throw new Exception(<<<ERROR
Unsupported type "{$params[http\REQ_FIELD_DATA_TYPE]}" of data.
ERROR
                    , errors\C_INVALID_DATA_TYPE);
                }

                $params[http\REQ_FIELD_HEADERS]['content-type'] = $dataType;
            }

            $reqOpts['http']['content'] = $params[http\REQ_FIELD_DATA];
        }

        if ($params[http\REQ_FIELD_HEADERS]) {

            $reqOpts['http']['header'] = join(http\PROTOCOL_DELIMITER, array_map(
                function(string $k, $v) {
                    return "{$k}: $v";
                },
                array_keys($params[http\REQ_FIELD_HEADERS]),
                array_values($params[http\REQ_FIELD_HEADERS])
            ));
        }

        $response = new Response();

        $startTime = microtime(true);

        $response->data = @file_get_contents(
            $params[http\REQ_FIELD_URL],
            false,
            stream_context_create($reqOpts)
        );

        if (!$response->data) {

            if (microtime(true) - $startTime >= $timeout) {

                throw new Exception(
                    'Request timeout.',
                    errors\C_TIMEOUT
                );
            }

            if (!$http_response_header) {

                throw new Exception(
                    'Failed to get response from server.',
                    errors\C_REQUEST_FAILURE
                );
            }

            $response->data = '';
        }

        if ($params[http\REQ_FIELD_GET_HEADERS] ?? false) {

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
