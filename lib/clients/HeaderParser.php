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

use L\Http as http;

class HeaderParser
{
    public static function parseCURLHeaders(
        array $headers
    ): array
    {
        $redirectHeaders = [];

        if (isset($headers[1])) {

            $finalHeader = self::parseHeaderArray(
                explode(
                    http\PROTOCOL_DELIMITER,
                    array_splice(
                        $headers,
                        -1
                    )[0]
                )
            );

            foreach ($headers as $redirection) {

                $redirectHeaders[] = self::parseHeaderArray(
                    explode(
                        http\PROTOCOL_DELIMITER,
                        $redirection
                    )
                );
            }
        }
        else {

            $finalHeader = self::parseHeaderArray(
                explode(
                    http\PROTOCOL_DELIMITER,
                    $headers[0]
                )
            );
        }

        return [$finalHeader, $redirectHeaders];
    }

    public static function parseHeaderArray(array $header): array
    {
        $statusHeader = array_splice(
            $header,
            0,
            1
        )[0];

        $header = array_map(

            function($item) {

                list($k, $v) = explode(
                    ':',
                    $item,
                    2
                );

                return [$k, trim($v)];
            },

            $header
        );

        $header = array_combine(
            array_column($header, 0),
            array_column($header, 1)
        );

        $header['HTTP-Version'] = floatval(substr($statusHeader, 5, 3));
        $header['HTTP-Status'] = intval(substr($statusHeader, 9, 3));
        $header['HTTP-Status-Message'] = substr($statusHeader, 13);

        return $header;
    }
}