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

namespace L\Http;

interface IClient
{
    /**
     * IClient constructor.
     *
     * @param array $configs
     *   Accepts following configuration items:
     *
     *     string caFile   Specify the path to CA bundle file.
     *
     *     bool strictSSL  Specify the strict checking for SSL/TLS.
     */
    public function __construct(
        array $configs = []
    );

    /**
     * Send a HTTP DELETE request.
     *
     * > The data parameter will be ignored.
     *
     * @param array $params
     *
     * @see IClient::request()
     *
     * @return Response
     */
    public function delete(
        array $params
    ): Response;

    /**
     * Send a HTTP GET request.
     *
     * > The data parameter will be ignored.
     *
     * @param array $params
     *
     * @see IClient::request()
     *
     * @return Response
     */
    public function get(
        array $params
    ): Response;

    /**
     * Send a HTTP HEAD request.
     *
     * > This request only returns the headers.
     * >
     * > The data parameter will be ignored.
     *
     * @param array $params
     *
     * @see IClient::request()
     *
     * @return Response
     */
    public function head(
        array $params
    ): Response;

    /**
     * Send a HTTP OPTIONS request.
     *
     * > The data parameter will be ignored.
     *
     * @param array $params
     *
     * @see IClient::request()
     *
     * @return Response
     */
    public function options(
        array $params
    ): Response;

    /**
     * Send a HTTP PATCH request.
     *
     * > The data parameter is required.
     *
     * @param array $params
     *
     * @see IClient::request()
     *
     * @return Response
     */
    public function patch(
        array $params
    ): Response;

    /**
     * Send a HTTP POST request.
     *
     * > The data parameter is required.
     *
     * @param array $params
     *
     * @see IClient::request()
     *
     * @return Response
     */
    public function post(
        array $params
    ): Response;

    /**
     * Send a HTTP PUT request.
     *
     * > The data parameter is required.
     *
     * @param array $params
     *
     * @see IClient::request()
     *
     * @return Response
     */
    public function put(
        array $params
    ): Response;

    /**
     * Send a HTTP request.
     *
     * @param string $method
     * @param array $params
     *
     *   The params supports following fields:
     *
     *   string url: The URL of target link.
     *
     *   bool getHeader [optional]: Desires HTTP headers, default: false.
     *
     *   bool getData [optional]: Desires HTTP body, default: true.
     *
     *   bool getProfile [optional]: Desires request profile, default: false.
     *
     *   bool strictSSL [optional]: Strict mode SSL, default: true.
     *
     *   string caFile [optional]: Customize the path to the CA bundle file.
     *
     *   string|array data [optional]: The data to send to server.
     *
     *   array headers [optional]: The customized headers to send to server.
     *
     * @throws \L\Core\Exception
     *
     * @return Response
     */
    public function request(
        string $method,
        array $params
    ): Response;
}
