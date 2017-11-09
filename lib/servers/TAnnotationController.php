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

use \L\Annotation\Extractor;

/**
 * Trait TAnnotationRouter
 *
 * @package litert/http
 *
 * @property string[] $annotations Get the annotations of current method.
 */
trait TAnnotationController
{
    protected function _initializeAnnotations()
    {
        $this->setInitializer(
            'annotations',
            function () {

                return self::__getMethodAnnotations(
                    $this->request->entryMethod
                );
            }
        );
    }

    public static function autoRoute(IRouter $router)
    {
        $annotations = Extractor::fromClass(
            static::class,
            true
        );

        foreach ($annotations as $name => $values) {

            switch ($name) {
            case 'http.post':
            case 'http.patch':
            case 'http.put':
            case 'http.get':
            case 'http.delete':
            case 'http.head':
            case 'http.options':

                $method = substr($name, 5);

                foreach ($values as $val) {

                    if (is_bool($val)) {
                        continue;
                    }
                    if (is_array($val)) {

                        if (isset($val['path'])) {

                            $router->$method(
                                $val['path'],
                                static::class
                            );
                        }
                        elseif ($val[0] ?? false){

                            $router->$method(
                                $val[0],
                                static::class
                            );
                        }
                    }
                    else {

                        $router->$method(
                            $val,
                            static::class
                        );
                    }

                    self::__getMethodAnnotations(
                        IServer::DEFAULT_ENTRY_METHOD,
                        $annotations
                    );
                }

                break;

            case 'http.notFound':

                $router->notFound(static::class);

                break;

            case 'http.badMethod':

                $router->badMethod(static::class);

                break;

            case 'http.hook':

                foreach ($values as $val) {

                    $router->hook(
                        $val['type'],
                        static::class,
                        $val['handler'],
                        IServer::DEFAULT_ENTRY_METHOD
                    );
                }

                break;

            default:

                static::__annotationsRouter(
                    $router,
                    $name,
                    IServer::DEFAULT_ENTRY_METHOD,
                    $values
                );
            }
        }
    }

    abstract protected static function __annotationsRouter(
        IRouter $router,
        string $name,
        string $entryName,
        $args
    );

    protected static function __getMethodAnnotations(
        string $methodName,
        array $classAnnotations = null
    ): array
    {
        $class = static::class;

        $annotations = apcu_fetch(
            "/L/http/controllers/{$class}/methods/{$methodName}/annotations",
            $result
        );

        if ($result) {

            return $annotations;
        }

        if ($classAnnotations === null) {

            $classAnnotations = Extractor::fromClass(
                static::class,
                true
            );
        }

        $annotations = Extractor::fromMethod(
            $methodName,
            $class = static::class,
            true
        );

        foreach ($classAnnotations as $key => $valueSet) {

            if (isset($annotations[$key])) {

                $annotations[$key] = array_merge(
                    $valueSet,
                    $annotations[$key]
                );
            }
            else {

                $annotations[$key] = $valueSet;
            }
        }

        apcu_store(
            "/L/http/controllers/{$class}/methods/{$methodName}/annotations",
            $annotations
        );

        return $annotations;
    }
}
