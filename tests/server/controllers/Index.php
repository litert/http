<?php
declare (strict_types = 1);

namespace Test\Server\Controller;

/**
 * @http.get(
 *   path = "/",
 *   entry = "main"
 * )
 * @http.hook(
 *   type = after-request,
 *   handler = afterRequestHandler
 * )
 */
class Index extends BaseController
{
    /**
     * @sample
     */
    public function main()
    {
        $this->response->startBuffer();

        if ($this->annotations['sample'] ?? false) {

            $this->response->writeLine('This is a sample');
        }

        $this->response->setStatusCode(
            201,
            'CREATED'
        );

        $this->response->writeHeader(
            'Content-Type',
            'text/plain; charset=utf-8'
        );

        $this->response->writeLine('Go go go go');
        $this->response->writeLine($_SERVER['REQUEST_URI']);

        var_dump($this->response->isHeaderSent());

        $this->response->writeLine("Visiting '{$this->request->path}'.");
        $this->response->writeLine('Hello world');
        $this->response->flushBuffer();
    }

    public function afterRequestHandler()
    {
        $this->response->writeLine('after request!');
    }
}
