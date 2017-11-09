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

class SimpleServer implements IServer
{
    /**
     * @var IContext
     */
    protected $context;

    public function __construct(IContext $context)
    {
        $this->context = $context;

        $this->context->setInitializer(
            'response',
            function() {

                return $this->context->factory->createResponse();
            }
        );
    }
    
    protected function _validatePath(string $path = null)
    {
        if ($path === null) {

            if (isset($_SERVER['REQUEST_PATH'])) {

                $path = $_SERVER['REQUEST_PATH'];
            }
            else {

                $path = explode(
                    '?',
                    $_SERVER['REQUEST_URI'],
                    2
                )[0];
            }
        }

        if (strlen($path) > 1 &&
            $path[-1] === '/'
        ) {

            $path = substr(
                $path,
                0,
                -1
            );
        }

        $this->context->requestPath = $path;
    }

    public function handle(string $path = null)
    {
        $ctx = $this->context;

        $this->_validatePath($path);

        $result = $this->context->router->route(
            $_SERVER['REQUEST_METHOD'],
            $ctx->requestPath
        );

        $ctx->setInitializer(
            'request',
            function() use ($result) {

                $req = $this->context->factory->createRequest();

                $req->method = $_SERVER['REQUEST_METHOD'];
                $req->clientIP = $_SERVER['REMOTE_ADDR'];
                $req->path = $this->context->requestPath;
                $req->pathArguments = $result['args'];
                $req->entryMethod = $result['entry'];

                return $req;
            }
        );

        $controller = new $result['controller']($ctx);

        try {

            if (isset($result['hooks']['before-request'])) {

                foreach ($result['hooks']['before-request'] as $hook) {

                    if (false === $controller->{$hook['method']}($hook['data'])) {

                        return;
                    }
                }
            }

            $controller->{$result['entry']}(...$result['args']);

            if (isset($result['hooks']['after-request'])) {

                foreach ($result['hooks']['after-request'] as $hook) {

                    if (false === $controller->{$hook['method']}($hook['data'])) {

                        return;
                    }
                }
            }
        }
        catch (\Throwable $e) {

            if (isset($result['hooks']['error'])) {

                foreach ($result['hooks']['error'] as $hook) {

                    if (false === $controller->$hook($e)) {

                        return;
                    }
                }
            }
            else {

                throw $e;
            }
        }
    }
}
