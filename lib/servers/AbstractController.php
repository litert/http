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

use \L\Annotation\Extractor,
    \L\Kits\DelayInit\TPropertyContainerEx;

/**
 * Class AbstractController
 *
 * @package litert/http
 *
 * @property string[] $annotations Get the annotations of current method.
 * @property Request $request
 * @property Response $response
 */
abstract class AbstractController implements IController
{
    use TPropertyContainerEx;

    /**
     * @var Context
     */
    protected $context;

    public function __construct(Context $context)
    {
        $this->_initializeDelayInit();

        $this->context = $context;

        $this->setInitializer(
            'request',

            function () {

                return $this->context->request;
            }
        );

        $this->setInitializer(
            'response',

            function () {

                return $this->context->response;
            }
        );

        $this->setInitializer(
            'annotations',
            function () {

                $class = static::class;
                $annotations = apcu_fetch(
                    "/lrt/http/server/simple-router/{$class}/{$this->request->entryMethod}",
                    $result
                );

                if ($result) {

                    return $annotations;
                }

                return [];
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

                    $router->$method(
                        $val['path'],
                        static::class,
                        $val['entry'] ?? 'main'
                    );

                    self::__registerMethodAnnotations(
                        $val['entry'] ?? 'main',
                        $annotations
                    );
                }

                break;

            case 'http.notFound':

                $router->notFound(static::class);

                self::__registerMethodAnnotations(
                    'main',
                    $annotations
                );

                break;

            case 'http.badMethod':

                $router->badMethod(static::class);

                self::__registerMethodAnnotations(
                    'main',
                    $annotations
                );

                break;

            case 'http.hook':

                foreach ($values as $val) {

                    $router->hook(
                        $val['type'],
                        static::class,
                        $val['handler']
                    );

                    self::__registerMethodAnnotations(
                        $val['handler'],
                        $annotations
                    );
                }

                break;
            }
        }
    }

    protected static function __registerMethodAnnotations(
        string $name,
        array $classAnnotations
    )
    {
        $annotations = Extractor::fromMethod(
            $name,
            $class = static::class,
            true
        );

        foreach ($classAnnotations as $key => $valueSet) {

            if (isset($annotations[$key])) {

                $annotations[$key] = array_merge(
                    $annotations[$key],
                    $valueSet
                );
            }
            else {

                $annotations[$key] = $valueSet;
            }
        }

        apcu_store(
            "/lrt/http/server/simple-router/{$class}/{$name}",
            $annotations
        );
    }
}
