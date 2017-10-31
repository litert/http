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
 *
 * @property string[] $headers
 */
class Response
{
    /**
     * Flag to determine whether output is buffering.
     *
     * @var bool
     */
    protected $_isBuffering = false;

    /**
     * Tells if HTTP headers are already sent.
     *
     * @return bool
     */
    public function isHeaderSent(): bool
    {
        static $sent;

        if (!$sent) {

            $sent = headers_sent();
        }

        return $sent;
    }
    /**
     * Start buffering the output data.
     */
    public function startBuffer()
    {
        if ($this->_isBuffering) {

            return;
        }

        $this->_isBuffering = true;
        ob_start();
    }

    /**
     * Send the buffered output data to clients, empty the buffer, and then
     * stop buffering.
     */
    public function endBuffer()
    {
        if ($this->_isBuffering) {

            $this->_isBuffering = false;
            ob_end_flush();
            flush();
        }
    }

    /**
     * Empty the output buffer, without sending to clients.
     */
    public function cleanOutputBuffer()
    {
        if ($this->_isBuffering) {

            $this->_isBuffering = false;
            ob_end_clean();
        }
    }

    /**
     * Get the buffered output data.
     *
     * @return string
     */
    public function getBuffer()
    {
        return $this->_isBuffering ? ob_get_contents() : null;
    }

    /**
     * Send the buffered output data to clients, and empty the buffer.
     */
    public function flushBuffer()
    {
        if ($this->_isBuffering) {

            ob_end_flush();
            flush();
            ob_start();
        }
    }

    /**
     * Tell if the output data is being buffered.
     *
     * @return bool
     */
    public function isBuffering(): bool
    {
        return $this->_isBuffering;
    }

    /**
     * Send a piece of HTTP header to client.
     *
     * @param string $key
     * @param string $value
     */
    public function writeHeader(string $key, string $value)
    {
        header("{$key}: {$value}");
    }

    /**
     * Send HTTP headers to client.
     *
     * @param string[] $headers
     */
    public function writeHeaders(array $headers)
    {
        foreach ($headers as $key => $value) {

            header("{$key}: {$value}");
        }
    }

    /**
     * @param int $code
     * @param string $message
     */
    public function setStatusCode(int $code, string $message)
    {
        header("HTTP/1.1 {$code} {$message}");
    }

    /**
     * Send the data to client.
     *
     * If the output buffer is enabled, the output will be buffered.
     *
     * @param string $data
     */
    public function write(string $data)
    {
        echo $data;
    }

    /**
     * Send a line of data to client.
     *
     * If the output buffer is enabled, the output will be buffered.
     *
     * @param string $data
     */
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
