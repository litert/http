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

interface IResponse
{
    /**
     * Tells if HTTP headers are already sent.
     *
     * @return bool
     */
    public function isHeaderSent(): bool;

    /**
     * Start buffering the output data.
     */
    public function startBuffer();

    /**
     * Send the buffered output data to clients, empty the buffer, and then
     * stop buffering.
     */
    public function endBuffer();

    /**
     * Empty the output buffer, without sending to clients.
     */
    public function cleanOutputBuffer();

    /**
     * Get the buffered output data.
     *
     * @return string
     */
    public function getBuffer();

    /**
     * Send the buffered output data to clients, and empty the buffer.
     */
    public function flushBuffer();

    /**
     * Tell if the output data is being buffered.
     *
     * @return bool
     */
    public function isBuffering(): bool;

    /**
     * Send a piece of HTTP header to client.
     *
     * @param string $key
     * @param string $value
     */
    public function writeHeader(string $key, string $value);

    /**
     * Send HTTP headers to client.
     *
     * @param string[] $headers
     */
    public function writeHeaders(array $headers);

    /**
     * @param int $code
     * @param string $message
     */
    public function setStatusCode(int $code, string $message);

    /**
     * Send the data to client.
     *
     * If the output buffer is enabled, the output will be buffered.
     *
     * @param string $data
     */
    public function writeLine(string $data);

    /**
     * Send a line of data to client.
     *
     * If the output buffer is enabled, the output will be buffered.
     *
     * @param string $data
     */
    public function write(string $data);
}