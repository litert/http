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

namespace L\Http\Server;

/**
 * Interface IRequest
 * @package L\Http\Server
 *
 * @property string $path
 * @property string[] $headers
 * @property string $entryMethod
 * @property string $method
 * @property string $clientIP
 * @property string[] $pathArguments
 */
interface IRequest
{
    public function getBodyAsJSON(bool $parse = true);

    public function getBodyAsForm();
}
