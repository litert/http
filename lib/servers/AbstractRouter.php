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

abstract class AbstractRouter implements IRouter
{
    const ROUTE_TYPE_REGEXP = 0x01;
    const ROUTE_TYPE_SMART = 0x02;
    const ROUTE_TYPE_TEXT = 0x03;

    const VAR_TYPE_STRING = 0x01;
    const VAR_TYPE_NUMERIC = 0x02;
    const VAR_TYPE_HEX_UINT = 0x03;
    const VAR_TYPE_BOOL = 0x04;

    /**
     * @var string[][]
     */
    protected $_regexpRules;

    /**
     * @var string[][]
     */
    protected $_plainRules;

    /**
     * @var array
     */
    protected $_hooks;

    /**
     * @var bool
     */
    protected $_loadedCache;

    /**
     * @var string[]
     */
    protected $_events;

    public function initialize()
    {
        $this->_hooks = [];
    }

    public function saveToCache()
    {
        apcu_store(static::CACHE_KEY, [
            'regexp-rules' => $this->_regexpRules,
            'plain-rules' => $this->_plainRules,
            'events' => $this->_events,
            'hooks' => $this->_hooks
        ]);

        return $this;
    }

    public function loadedFromCache(): bool
    {
        $rules = apcu_fetch(
            static::CACHE_KEY,
            $this->_loadedCache
        );

        if ($this->_loadedCache) {

            $this->_regexpRules = $rules['regexp-rules'];
            $this->_plainRules = $rules['plain-rules'];
            $this->_events = $rules['events'];
            $this->_hooks = $rules['hooks'];
        }

        return $this->_loadedCache;
    }

    public function notFound($controller)
    {
        $this->_events['NOT_FOUND'] = $controller;
    }

    public function badMethod($controller)
    {
        $this->_events['BAD_METHOD'] = $controller;
    }

    protected function _compileSmartExpr(string $expr)
    {
        $pls = [];

        $expr = preg_replace_callback(
            '~\{(.+?)(:(.+?))?\}~',
            function(array $matches) use (&$pls): string {

                $name = $matches[1];
                $type = $matches[3] ?? 'string';

                $pls[$name] = $type;

                switch ($type) {
                case 'string':
                    $pls[$name] = self::VAR_TYPE_STRING;
                    return '([^/]+)';
                case 'hex-string':
                    $pls[$name] = self::VAR_TYPE_STRING;
                    return '([\dA-Fa-f]+)';
                case 'uint':
                    $pls[$name] = self::VAR_TYPE_NUMERIC;
                    return '(\d+)';
                case 'hex-uint':
                    $pls[$name] = self::VAR_TYPE_HEX_UINT;
                    return '([\dA-Fa-f]+)';
                case 'int':
                    $pls[$name] = self::VAR_TYPE_NUMERIC;
                    return '(\+?\d+|-\d+)';
                case 'float':
                    $pls[$name] = self::VAR_TYPE_NUMERIC;
                    return '(\+?\d+\.\d+|-\d+\.\d+|\+?\d+|-\d+)';
                case 'boolean':
                case 'bool':
                    $pls[$name] = self::VAR_TYPE_BOOL;
                    return '(true|false|on|off|1|0)';
                }

                if (preg_match(
                    '~^string\[(\d+)\]$~',
                    $type,
                    $result
                )) {

                    $pls[$name] = self::VAR_TYPE_STRING;
                    return '(.+{' . $result[1] . '})';
                }
                elseif (preg_match(
                    '~^hex-string\[(\d+)\]$~',
                    $type,
                    $result
                )) {

                    $pls[$name] = self::VAR_TYPE_STRING;
                    return '([\dA-Fa-f]{' . $result[1] . '})';
                }
                else {

                    throw new Exception(
                        "Invalid expression '{$type}' for variable type.",
                        Exception::E_INVALID_SMART_VARIABLE_TYPE
                    );
                }
            },
            $expr
        );

        return ["~^{$expr}$~", array_values($pls)];
    }

    public function hook(
        string $type,
        $controller,
        string $handler,
        string $method = IServer::DEFAULT_ENTRY_METHOD,
        array $data = []
    )
    {
        $place = "{$controller}::{$method}";

        if (empty($this->_hooks[$place])) {

            $this->_hooks[$place] = [];
        }

        if (empty($this->_hooks[$place][$type])) {

            $this->_hooks[$place][$type] = [
                ['method' => $handler, 'data' => $data]
            ];
        }
        else {

            $this->_hooks[$place][$type][] = [
                'method' => $handler,
                'data' => $data
            ];
        }
    }

    abstract public function route(
        string $method,
        string $path
    ): array;
}
