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

/**
 * Class Request
 *
 * @package litert/http
 *
 * @property string[] $headers
 */
class Request
{
    use \L\Kits\DelayInit\TPropertyContainerEx;

    /**
     * @var string
     */
    public $path;

    /**
     * @var string
     */
    public $entryMethod;

    /**
     * @var string
     */
    public $method;

    /**
     * @var string
     */
    public $clientIP;

    /**
     * @var string[]
     */
    public $pathArguments;

    public function __construct()
    {
        $this->_initializeDelayInit();

        $this->setInitializer(
            'headers',
            'getallheaders'
        );
    }

    public function getBodyAsJSON(bool $parse = true)
    {
        $data = file_get_contents('php://input');

        if ($data !== '') {

            return $parse ? json_decode($data, true) : $data;
        }

        return $parse ? null : '';
    }

    public function getBodyAsForm()
    {
        return $_POST;
    }
}
