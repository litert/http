<?php
declare (strict_types = 1);

namespace Test\Server\Controller;

use \L\Http\Server\TAnnotationPolyController,
    \L\Http\Server\IRouter,
    \L\Http\Server\AbstractController;
class RESTFul extends AbstractController
{
    use TAnnotationPolyController;

    /**
     * @http.get("/rest")
     * @http.post("/rest")
     */
    public function main()
    {
        $this->response->writeLine($this->request->method);
    }

    protected static function __annotationsRouter(
        IRouter $router,
        string $name,
        string $entryName,
        $args
    ) {

    }
}
