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

class SimpleFactory implements IFactory
{
    public function createRouter(): IRouter
    {
        return new SimpleRouter();
    }

    public function createContext(): IContext
    {
        return new SimpleContext($this);
    }

    public function createServer(
        IContext $ctx
    ): IServer
    {
        return new SimpleServer($ctx);
    }

    public function createRequest(): IRequest
    {
        return new SimpleRequest();
    }

    public function createResponse(): IResponse
    {
        return new SimpleResponse();
    }
}
