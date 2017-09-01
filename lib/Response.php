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

declare (strict_types=1);

namespace L\Http;

class Response
{
    /**
     * @var int
     */
    public $code;

    /**
     * @var string
     */
    public $data;

    /**
     * @var array|null
     */
    public $headers;

    /**
     * @var array|null
     */
    public $profile;

    /**
     * @var array|null
     */
    public $redirectionHeaders;

    public function __construct(array $resp)
    {
        foreach ($resp as $key => $item) {
            $this->$key = $item;
        }
    }

    public function isServerError(): bool
    {
        return $this->code >= 500;
    }

    public function isClientError(): bool
    {
        return $this->code >= 400 && $this->code < 500;
    }

    public function isSuccess(): bool
    {
        return $this->code >= CODE_OK && $this->code < 400;
    }
}