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

interface IFactory
{
    /**
     * Create a router object.
     *
     * @return IRouter
     */
    public function createRouter(): IRouter;

    /**
     * Create a HTTP context object.
     *
     * @return IContext
     */
    public function createContext(): IContext;

    /**
     * Create a HTTP server controlling object.
     *
     * @param IContext $ctx
     *
     * @return IServer
     */
    public function createServer(
        IContext $ctx
    ): IServer;

    /**
     * Create a HTTP request controlling object.
     *
     * @return IRequest
     */
    public function createRequest(): IRequest;

    /**
     * Create a HTTP response controlling object.
     *
     * @return IResponse
     */
    public function createResponse(): IResponse;
}
