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

declare (strict_types=1);

namespace L\Http;

const HTTP_EOL = "\r\n";

const HTTP_SEG_SEPARATOR = "\r\n\r\n";

const AVAILABLE_METHODS = [
    'GET' => 1,
    'POST' => 1,
    'DELETE' => 1,
    'PUT' => 1,
    'PATCH' => 1,
    'HEAD' => 1,
    'OPTIONS' => 1
];

const METHOD_CONTENT_SUPPORTS = [
    'GET' => false,
    'POST' => true,
    'PATCH' => true,
    'PUT' => true,
    'DELETE' => false,
    'HEAD' => false,
    'OPTIONS' => false
];

const CODE_CONTINUE = 100;
const CODE_OK = 200;
const CODE_CREATED = 201;
const CODE_PARTIAL = 206;
const CODE_MULTI_CHOICES = 300;
const CODE_BAD_REQUEST = 400;
const CODE_INTERNAL_SERVER_ERROR = 500;

const DEFAULT_DATA_CONTENT_TYPE = 'application/x-www-form-urlencoded';
const JSON_CONTENT_TYPE = 'application/json';

const REQ_FIELD_URL = 'url';
const REQ_FIELD_HEADERS = 'headers';
const REQ_FIELD_DATA = 'data';
const REQ_FIELD_DATA_TYPE = 'dataType';
const REQ_FIELD_CA_FILE = 'caFile';
const REQ_FIELD_STRICT_SSL = 'strictSSL';
const REQ_FIELD_GET_DATA = 'getData';
const REQ_FIELD_GET_HEADERS = 'getHeaders';
const REQ_FIELD_GET_PROFILE = 'getProfile';

const E_LACK_FIELD_URL = 0x0001;
const E_METHOD_UNSUPPORTED = 0x0002;
const E_LACK_FIELD_DATA = 0x0003;
const E_INVALID_DATA_TYPE = 0x0004;
const E_REQUEST_FAILURE = 0x0005;
const E_VERSION_UNSUPPORTED = 0x0006;
