<?php
declare (strict_types = 1);

namespace Test\Server\Controller;

use L\Http\Server\IRouter;
use L\Http\Server as server;

/**
 * Class TestHandler
 *
 * @package litert/http
 *
 * @http.get(path="/test")
 */
class Test extends server\AbstractController
{
    public function main()
    {
        echo 'Hello world';
    }
}
