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
    public $previousHeaders;

    /**
     * Judge if the HTTP status code is 1xx.
     *
     * @return bool
     */
    public function isMessage(): bool
    {
        return $this->code >= CODE_CONTINUE &&
            $this->code < CODE_OK;
    }

    /**
     * Judge if the HTTP status code is 2xx.
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->code >= CODE_OK && $this->code < CODE_BAD_REQUEST;
    }

    /**
     * Judge if the HTTP status code is 3xx.
     *
     * @return bool
     */
    public function isRedirection(): bool
    {
        return $this->code >= CODE_BAD_REQUEST &&
            $this->code < CODE_INTERNAL_SERVER_ERROR;
    }

    /**
     * Judge if the HTTP status code is 4xx.
     *
     * @return bool
     */
    public function isClientError(): bool
    {
        return $this->code >= CODE_BAD_REQUEST &&
            $this->code < CODE_INTERNAL_SERVER_ERROR;
    }

    /**
     * Judge if the HTTP status code is 5xx.
     *
     * @return bool
     */
    public function isServerError(): bool
    {
        return $this->code >= CODE_INTERNAL_SERVER_ERROR;
    }
}
