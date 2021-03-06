<?php
declare (strict_types = 1);

namespace Test\Server\Controller;

use \L\Http\Server\TAnnotationController,
    \L\Http\Server\IRouter,
    \L\Http\Server\AbstractController;

/**
 * @http.notFound
 */
class NotFound extends AbstractController
{
    use TAnnotationController;

    public function main()
    {
        header('HTTP/1.1 404 NOT FOUND');
        echo 'FILE NOT FOUND~';
    }

    protected static function __annotationsRouter(
        IRouter $router,
        string $name,
        string $entryName,
        $args
    ) {

    }
}
