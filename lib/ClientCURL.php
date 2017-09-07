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

class ClientCURL extends AbstractClient
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

        $ch = curl_init();

        $timeout = ($params[REQ_FIELD_TIMEOUT] ?? $this->timeout) * 1000;

        $reqOpts = [
            CURLOPT_URL => $params[REQ_FIELD_URL],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => intval($params[REQ_FIELD_GET_HEADERS] ?? false),
            CURLOPT_NOBODY => intval(!($params[REQ_FIELD_GET_DATA] ?? true)),
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_TIMEOUT_MS => $timeout,
            CURLOPT_CONNECTTIMEOUT_MS => $timeout,
            CURLOPT_NOSIGNAL => $timeout <= 1000 ? 1 : 0
        ];

        switch ($version = $params[REQ_FIELD_VERSION] ?? $this->version) {
        case 1.0:
            $reqOpts[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_0;
            break;
        case 1.1:
            $reqOpts[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1;
            break;
        default:

            throw new Exception(<<<ERROR
Version "{$version}" of HTTP protocol is not supported yet.
ERROR
            , E_VERSION_UNSUPPORTED);
        }

        $isHTTPS = substr($params[REQ_FIELD_URL], 0, 5) === 'https';

        if ($isHTTPS && ($params[REQ_FIELD_STRICT_SSL] ?? $this->strictSSL)) {

            $reqOpts[CURLOPT_SSL_VERIFYPEER] = true;
            $reqOpts[CURLOPT_SSL_VERIFYHOST] = 2;

            if ($caFile = $params[REQ_FIELD_CA_FILE] ?? $this->caFile) {

                $reqOpts[CURLOPT_CAINFO] = $caFile;
            }
        }
        else {

            $reqOpts[CURLOPT_SSL_VERIFYPEER] = false;
            $reqOpts[CURLOPT_SSL_VERIFYHOST] = 0;
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

        switch (self::METHOD_MAPPING[$method]) {
        case CURLOPT_POST:
            $reqOpts[CURLOPT_POST] = true;
            break;
        case CURLOPT_CUSTOMREQUEST:
            $reqOpts[CURLOPT_CUSTOMREQUEST] = $method;
            break;
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
                    $params[REQ_FIELD_DATA] = json_encode($params[REQ_FIELD_DATA]);
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

            $reqOpts[CURLOPT_POSTFIELDS] = $params[REQ_FIELD_DATA];
        }
        else {

            /**
             * HEAD method returns no body but headers.
             *
             * CURL will be stuck if getData is set to TRUE.
             */
            if ($method === 'HEAD') {

                $reqOpts[CURLOPT_NOBODY] = 0;
            }
        }

        if ($params[REQ_FIELD_HEADERS]) {

            $reqOpts[CURLOPT_HTTPHEADER] = array_map(
                function(string $k, $v) {
                    return "{$k}: $v";
                },
                array_keys($params[REQ_FIELD_HEADERS]),
                array_values($params[REQ_FIELD_HEADERS])
            );
        }

        curl_setopt_array($ch, $reqOpts);

        unset($reqOpts);

        $response = new Response();

        $response->data = curl_exec($ch);

        $info = curl_getinfo($ch);

        curl_close($ch);

        $response->code = $info['http_code'];

        if ($response->data === false) {

            if ($response->code === 0) {

                throw new Exception('Request timeout.', E_TIMEOUT);
            }

            throw new Exception(curl_error($ch), E_REQUEST_FAILURE);
        }

        if ($params[REQ_FIELD_GET_HEADERS] ?? false) {

            $fullHeaderLength = $info['header_size'];

            $response->headers = explode(SEGMENT_DELIMITER, substr(
                $response->data,
                0,
                $fullHeaderLength - 4
            ));

            if ($params[REQ_FIELD_GET_DATA] ?? true) {

                $response->data = substr($response->data, $fullHeaderLength);
            }
            else {

                $response->data = '';
            }

            unset($fullHeaderLength);

            list(

                $response->headers,
                $response->previousHeaders

            ) = HeaderParser::parseCURLHeaders(
                $response->headers
            );
        }

        if ($params[REQ_FIELD_GET_PROFILE] ?? false) {

            $response->profile = $info;
        }

        return $response;
    }
}