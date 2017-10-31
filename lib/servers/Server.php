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

use L\Http\IServer;

class Server implements IServer
{
    /**
     * @var Context
     */
    protected $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function handle(string $path = null)
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

        if (strlen($path) > 1 && $path[-1] === '/') {

            $path = substr($path, 0, -1);
        }

        $result = $this->context->router->route(
            $_SERVER['REQUEST_METHOD'],
            $path
        );

        $this->context->setInitializer(
            'request',
            function() use ($path, $result) {

                $req = new Request();

                $req->method = $_SERVER['REQUEST_METHOD'];
                $req->clientIP = $_SERVER['REMOTE_ADDR'];
                $req->path = $path;
                $req->pathArguments = $result['args'];
                $req->entryMethod = $result['entry'];

                return $req;
            }
        );

        $this->context->setInitializer(
            'response',
            function() {

                return new Response();
            }
        );

        $controller = new $result['controller']($this->context);

        try {

            if (isset($result['hooks']['before-request'])) {

                foreach ($result['hooks']['before-request'] as $hook) {

                    $controller->$hook(...$result['args']);
                }
            }

            $controller->{$result['entry']}(...$result['args']);

            if (isset($result['hooks']['after-request'])) {

                foreach ($result['hooks']['after-request'] as $hook) {

                    $controller->$hook(...$result['args']);
                }
            }
        }
        catch (\Exception $e) {

            if (isset($result['hooks']['error'])) {

                foreach ($result['hooks']['error'] as $hook) {

                    $controller->$hook($e);
                }
            }
        }
    }
}
