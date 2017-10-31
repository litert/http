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

class Router extends AbstractRouter
{
    const CACHE_KEY = '/lrt/http/server/simple-router';

    public function initialize()
    {
        parent::initialize();

        $this->_plainRules = $this->_regexpRules = [
            'GET' => [],
            'POST' => [],
            'PATCH' => [],
            'PUT' => [],
            'DELETE' => [],
            'OPTIONS' => [],
            'HEAD' => []
        ];

        $this->_events = [];
    }

    public function route(string $method, string $path): array
    {
        $pathArgs = [];

        if (!is_array($this->_regexpRules[$method] ?? false)) {

            $ctrlClass = $this->_events['BAD_METHOD'];
            goto onFinal;
        }

        if ($rule = $this->_plainRules[$method][$path] ?? false) {

            $ctrlClass = $rule['controller'];
            $entry = $rule['entry'];
            goto onFinal;
        }

        $rules = $this->_regexpRules[$method];

        foreach ($rules as $rule) {

            $matched = false;

            switch ($rule['type']) {
            case self::ROUTE_TYPE_REGEXP:
                $matched = preg_match($rule['path'], $path);
                break;
            case self::ROUTE_TYPE_SMART:

                if (!preg_match($rule['path'], $path, $result)) {

                    continue;
                }

                $matched = true;

                foreach ($rule['placeholders'] as $index => $ph) {

                    switch ($ph) {

                    case self::VAR_TYPE_NUMERIC:

                        $pathArgs[] = 0 + $result[1 + $index];

                        break;

                    case self::VAR_TYPE_HEX_UINT:

                        $pathArgs[] = intval(
                            $result[1 + $index],
                            16
                        );

                        break;

                    case self::VAR_TYPE_BOOL:

                        switch (strtolower($result[1 + $index])) {
                        case 'true':
                        case 'on':
                        case '1':

                            $pathArgs[] = true;
                            break;

                        default:

                            $pathArgs[] = false;
                            break;
                        }

                        break;

                    default:

                        $pathArgs[] = $result[1 + $index];

                        break;
                    }
                }

                break;
            }

            if ($matched) {

                $ctrlClass = $rule['controller'];
                $entry = $rule['entry'];
                goto onFinal;
            }
        }

        $ctrlClass = $this->_events['NOT_FOUND'];

onFinal:

        return [
            'args' => $pathArgs,
            'controller' => $ctrlClass,
            'entry' => $entry ?? 'main',
            'hooks' => $this->_hooks[$ctrlClass] ?? []
        ];
    }

    protected function _addRule(
        string $method,
        string $uri,
        $controller,
        string $entry
    )
    {
        if ($uri[0] === '~') {

            $this->_regexpRules[$method][$uri] = [
                'uri' => $uri,
                'controller' => $controller,
                'type' => self::ROUTE_TYPE_REGEXP,
                'entry' => $entry
            ];
        }
        elseif (strpos($uri, '{') !== false) {

            $rule = [
                'type' => self::ROUTE_TYPE_SMART,
                'controller' => $controller,
                'entry' => $entry
            ];

            list($rule['path'], $rule['placeholders']) = $this->_compileSmartExpr($uri);

            $this->_regexpRules[$method][$uri] = $rule;
        }
        else {

            $this->_plainRules[$method][$uri] = [
                'controller' => $controller,
                'entry' => $entry
            ];
        }
    }

    public function get(string $uri, $controller, string $entry = 'main')
    {
        $this->_addRule(
            'GET',
            $uri,
            $controller,
            $entry
        );
    }

    public function post(string $uri, $controller, string $entry = 'main')
    {
        $this->_addRule(
            'POST',
            $uri,
            $controller,
            $entry
        );
    }

    public function put(string $uri, $controller, string $entry = 'main')
    {
        $this->_addRule(
            'PUT',
            $uri,
            $controller,
            $entry
        );
    }

    public function patch(string $uri, $controller, string $entry = 'main')
    {
        $this->_addRule(
            'PATCH',
            $uri,
            $controller,
            $entry
        );
    }

    public function options(string $uri, $controller, string $entry = 'main')
    {
        $this->_addRule(
            'OPTIONS',
            $uri,
            $controller,
            $entry
        );
    }

    public function head(string $uri, $controller, string $entry = 'main')
    {
        $this->_addRule(
            'HEAD',
            $uri,
            $controller,
            $entry
        );
    }

    public function delete(string $uri, $controller, string $entry = 'main')
    {
        $this->_addRule(
            'DELETE',
            $uri,
            $controller,
            $entry
        );
    }
}
