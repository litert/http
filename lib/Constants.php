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

const CODE_OK = 200;
const CODE_CREATED = 201;
const CODE_PARTIAL = 206;
