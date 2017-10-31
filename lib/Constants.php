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

const PROTOCOL_DELIMITER = "\r\n";

const SEGMENT_DELIMITER = "\r\n\r\n";

const CLIENT_AVAILABLE_METHODS = [
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
const CODE_SWITCHING_PROTOCOL = 101;
const CODE_OK = 200;
const CODE_CREATED = 201;
const CODE_ACCEPTED = 202;
const CODE_NO_CONTENT = 204;
const CODE_PARTIAL = 206;
const CODE_MULTI_CHOICES = 300;
const CODE_MOVED_PERMANENTLY = 301;
const CODE_FOUND = 302;
const CODE_SEE_OTHER = 303;
const CODE_NOT_MODIFIED = 304;
const CODE_USE_PROXY = 305;
const CODE_TEMPORARY_REDIRECT = 307;
const CODE_BAD_REQUEST = 400;
const CODE_UNAUTHORIZED = 401;
const CODE_PAYMENT_REQUIRED = 402;
const CODE_FORBIDDEN = 403;
const CODE_NOT_FOUND = 404;
const CODE_METHOD_NOT_ALLOWED = 405;
const CODE_NOT_ACCEPTABLE = 406;
const CODE_PROXY_UNAUTHORIZED = 407;
const CODE_REQUEST_TIMEOUT = 408;
const CODE_CONFLICT = 409;
const CODE_GONE = 410;
const CODE_LENGTH_REQUIRED = 411;
const CODE_PRECONDITION_FAILED = 412;
const CODE_ENTITY_TOO_LARGE = 413;
const CODE_URI_TOO_LONG = 414;
const CODE_UNSUPPORTED_MEDIA_TYPE = 415;
const CODE_RANGE_NOT_SATISFIABLE = 416;
const CODE_EXPECTATION_FAILED = 417;
const CODE_INTERNAL_SERVER_ERROR = 500;
const CODE_NOT_IMPLEMENTED = 501;
const CODE_BAD_GATEWAY = 502;
const CODE_SERVER_UNAVAILABLE = 503;
const CODE_GATEWAY_TIMEOUT = 504;
const CODE_VERSION_UNSUPPORTED = 505;

const DEFAULT_DATA_CONTENT_TYPE = 'application/x-www-form-urlencoded';
const JSON_CONTENT_TYPE = 'application/json';
const DEFAULT_TIMEOUT = 30;
const DEFAULT_STRICT_SSL = true;
const DEFAULT_VERSION = 1.1;

const REQ_FIELD_URL = 'url';
const REQ_FIELD_HEADERS = 'headers';
const REQ_FIELD_DATA = 'data';
const REQ_FIELD_DATA_TYPE = 'dataType';
const REQ_FIELD_CA_FILE = 'caFile';
const REQ_FIELD_STRICT_SSL = 'strictSSL';
const REQ_FIELD_GET_DATA = 'getData';
const REQ_FIELD_GET_HEADERS = 'getHeaders';
const REQ_FIELD_GET_PROFILE = 'getProfile';
const REQ_FIELD_TIMEOUT = 'timeout';
const REQ_FIELD_VERSION = 'version';
