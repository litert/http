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

interface IRouter
{
    /**
     * Do route the path and return the result.
     *
     * @param string $method
     *
     * @param string $path
     *
     * @return array Returns an array contains 4 fields:
     *   hooks, args, controller, entry
     */
    public function route(string $method, string $path): array;

    /**
     * Register the controller when no route rules matched.
     *
     * @param $controller
     * @return void
     */
    public function notFound($controller);

    /**
     * Register the controller when requested method is not supported.
     *
     * @param $controller
     * @return void
     */
    public function badMethod($controller);

    /**
     * Save current route rules into cache.
     *
     * @return IRouter
     */
    public function saveToCache();

    /**
     * Load route rules from cache.
     *
     * @return bool
     *   Return true if some rules were loaded. Otherwise, false is returned.
     */
    public function loadedFromCache(): bool;

    /**
     * Initialize the route rules storage.
     *
     * @return mixed
     */
    public function initialize();

    /**
     * Register a GET request handler.
     *
     * @param string $uri
     * @param $controller
     * @param string $entry
     *
     * @return void
     */
    public function get(string $uri, $controller, string $entry = 'main');

    /**
     * Register a POST request handler.
     *
     * @param string $uri
     * @param $controller
     * @param string $entry
     *
     * @return void
     */
    public function post(string $uri, $controller, string $entry = 'main');

    /**
     * Register a PUT request handler.
     *
     * @param string $uri
     * @param $controller
     * @param string $entry
     *
     * @return void
     */
    public function put(string $uri, $controller, string $entry = 'main');

    /**
     * Register a PATCH request handler.
     *
     * @param string $uri
     * @param $controller
     * @param string $entry
     *
     * @return void
     */
    public function patch(string $uri, $controller, string $entry = 'main');

    /**
     * Register a OPTIONS request handler.
     *
     * @param string $uri
     * @param $controller
     * @param string $entry
     *
     * @return void
     */
    public function options(string $uri, $controller, string $entry = 'main');

    /**
     * Register a HEAD request handler.
     *
     * @param string $uri
     * @param $controller
     * @param string $entry
     *
     * @return void
     */
    public function head(string $uri, $controller, string $entry = 'main');

    /**
     * Register a DELETE request handler.
     *
     * @param string $uri
     * @param $controller
     * @param string $entry
     *
     * @return void
     */
    public function delete(string $uri, $controller, string $entry = 'main');

    /**
     * Register a hook handler.
     *
     * @param string $type
     * @param $controller
     * @param string $handler
     *
     * @return void
     */
    public function hook(
        string $type,
        $controller,
        string $handler
    );
}
