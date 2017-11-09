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
 * Class Response
 *
 * @package litert/http
 */
class SimpleResponse implements IResponse
{
    /**
     * Flag to determine whether output is buffering.
     *
     * @var bool
     */
    protected $_isBuffering = false;

    public function isHeaderSent(): bool
    {
        static $sent;

        if (!$sent) {

            $sent = headers_sent();
        }

        return $sent;
    }

    public function startBuffer()
    {
        if ($this->_isBuffering) {

            return;
        }

        $this->_isBuffering = true;
        ob_start();
    }

    public function endBuffer()
    {
        if ($this->_isBuffering) {

            $this->_isBuffering = false;
            ob_end_flush();
            flush();
        }
    }

    public function cleanOutputBuffer()
    {
        if ($this->_isBuffering) {

            $this->_isBuffering = false;
            ob_end_clean();
        }
    }

    public function getBuffer()
    {
        return $this->_isBuffering ? ob_get_contents() : null;
    }

    public function flushBuffer()
    {
        if ($this->_isBuffering) {

            ob_end_flush();
            flush();
            ob_start();
        }
    }

    public function isBuffering(): bool
    {
        return $this->_isBuffering;
    }

    public function writeHeader(string $key, string $value)
    {
        header("{$key}: {$value}");
    }

    public function writeHeaders(array $headers)
    {
        foreach ($headers as $key => $value) {

            header("{$key}: {$value}");
        }
    }

    public function setStatusCode(int $code, string $message)
    {
        header("HTTP/1.1 {$code} {$message}");
    }

    public function write(string $data)
    {
        echo $data;
    }

    public function writeLine(string $data)
    {
        echo $data, PHP_EOL;
    }

    /**
     * Flush buffer when request completed automatically.
     */
    public function __destruct()
    {
        $this->endBuffer();
    }
}
