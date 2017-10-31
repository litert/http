<?php
declare (strict_types = 1);

namespace Test\Server\Controller;

use L\Http\Server as server;

/**
 * @http.hook(type = before-request, handler = validate)
 */
abstract class BaseController extends server\AbstractController
{
    public function validate()
    {
    }
}
