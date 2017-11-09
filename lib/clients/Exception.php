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

class Exception extends \L\Core\Exception
{
    const E_LACK_FIELD_URL = 0x00000001;
    const E_METHOD_UNSUPPORTED = 0x00000002;
    const E_LACK_FIELD_DATA = 0x00000003;
    const E_INVALID_DATA_TYPE = 0x00000004;
    const E_REQUEST_FAILURE = 0x00000005;
    const E_VERSION_UNSUPPORTED = 0x00000006;
    const E_TIMEOUT = 0x00000007;
}
