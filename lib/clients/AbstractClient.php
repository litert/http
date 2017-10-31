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

namespace L\Http\Client;

use \L\Http as http;

abstract class AbstractClient implements http\IClient
{
    /**
     * @var string
     */
    public $caFile;

    /**
     * @var bool
     */
    public $strictSSL;

    /**
     * @var float
     */
    public $timeout;

    /**
     * @var int
     */
    public $version;

    public function __construct(array $config = [])
    {
        $this->strictSSL = $config[http\REQ_FIELD_STRICT_SSL] ?? http\DEFAULT_STRICT_SSL;

        $this->version = $config[http\REQ_FIELD_VERSION] ?? http\DEFAULT_VERSION;

        $this->timeout = $config[http\REQ_FIELD_TIMEOUT] ?? http\DEFAULT_TIMEOUT;

        $this->caFile = $config[http\REQ_FIELD_CA_FILE] ?? null;
    }

    public function delete(array $params): Response
    {
        return $this->request('DELETE', $params);
    }

    public function get(array $params): Response
    {
        return $this->request('GET', $params);
    }

    public function head(array $params): Response
    {
        return $this->request('HEAD', $params);
    }

    public function options(array $params): Response
    {
        return $this->request('OPTIONS', $params);
    }

    public function patch(array $params): Response
    {
        return $this->request('PATCH', $params);
    }

    public function post(array $params): Response
    {
        return $this->request('POST', $params);
    }

    public function put(array $params): Response
    {
        return $this->request('PUT', $params);
    }

    abstract public function request(
        string $method,
        array $params
    ): Response;
}
