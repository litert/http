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

class CURLAPI extends AbstractClient
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

        $ch = curl_init();

        $timeout = ($params[http\REQ_FIELD_TIMEOUT] ?? $this->timeout) * 1000;

        $reqOpts = [
            CURLOPT_URL => $params[http\REQ_FIELD_URL],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => intval($params[http\REQ_FIELD_GET_HEADERS] ?? false),
            CURLOPT_NOBODY => intval(!($params[http\REQ_FIELD_GET_DATA] ?? true)),
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_TIMEOUT_MS => $timeout,
            CURLOPT_CONNECTTIMEOUT_MS => $timeout,
            CURLOPT_NOSIGNAL => $timeout <= 1000 ? 1 : 0
        ];

        switch ($version = $params[http\REQ_FIELD_VERSION] ?? $this->version) {
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
            , errors\C_VERSION_UNSUPPORTED);
        }

        $isHTTPS = substr($params[http\REQ_FIELD_URL], 0, 5) === 'https';

        if ($isHTTPS && ($params[http\REQ_FIELD_STRICT_SSL] ?? $this->strictSSL)) {

            $reqOpts[CURLOPT_SSL_VERIFYPEER] = true;
            $reqOpts[CURLOPT_SSL_VERIFYHOST] = 2;

            if ($caFile = $params[http\REQ_FIELD_CA_FILE] ?? $this->caFile) {

                $reqOpts[CURLOPT_CAINFO] = $caFile;
            }
        }
        else {

            $reqOpts[CURLOPT_SSL_VERIFYPEER] = false;
            $reqOpts[CURLOPT_SSL_VERIFYHOST] = 0;
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

        switch (self::METHOD_MAPPING[$method]) {
        case CURLOPT_POST:
            $reqOpts[CURLOPT_POST] = true;
            break;
        case CURLOPT_CUSTOMREQUEST:
            $reqOpts[CURLOPT_CUSTOMREQUEST] = $method;
            break;
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
                    $params[http\REQ_FIELD_DATA] = json_encode($params[http\REQ_FIELD_DATA]);
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

            $reqOpts[CURLOPT_POSTFIELDS] = $params[http\REQ_FIELD_DATA];
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

        if ($params[http\REQ_FIELD_HEADERS]) {

            $reqOpts[CURLOPT_HTTPHEADER] = array_map(
                function(string $k, $v) {
                    return "{$k}: $v";
                },
                array_keys($params[http\REQ_FIELD_HEADERS]),
                array_values($params[http\REQ_FIELD_HEADERS])
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

                throw new Exception('Request timeout.', errors\C_TIMEOUT);
            }

            throw new Exception(curl_error($ch), errors\C_REQUEST_FAILURE);
        }

        if ($params[http\REQ_FIELD_GET_HEADERS] ?? false) {

            $fullHeaderLength = $info['header_size'];

            $response->headers = explode(http\SEGMENT_DELIMITER, substr(
                $response->data,
                0,
                $fullHeaderLength - 4
            ));

            if ($params[http\REQ_FIELD_GET_DATA] ?? true) {

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

        if ($params[http\REQ_FIELD_GET_PROFILE] ?? false) {

            $response->profile = $info;
        }

        return $response;
    }
}