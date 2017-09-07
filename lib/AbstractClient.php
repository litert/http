<?php
declare (strict_types=1);
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/7
 * Time: 15:43
 */

namespace L\Http;


abstract class AbstractClient implements IClient
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
        $this->strictSSL = $config['strictSSL'] ?? DEFAULT_STRICT_SSL;

        $this->version = $config['version'] ?? DEFAULT_VERSION;

        $this->timeout = $config['timeout'] ?? DEFAULT_TIMEOUT;

        $this->caFile = $config['caFile'] ?? null;
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
