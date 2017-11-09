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

use \L\Kits\DelayInit\TPropertyContainerEx;

/**
 * Class AbstractController
 *
 * @package litert/http
 *
 * @property IRequest $request
 * @property IResponse $response
 */
abstract class AbstractController implements IController
{
    use TPropertyContainerEx;

    /**
     * @var IContext
     */
    protected $context;

    public function __construct(IContext $context)
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
    }
}
