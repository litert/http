<?php
declare (strict_types = 1);

namespace Test\Server\Controller;

/**
 * @http.notFound
 */
class NotFound extends \L\Http\Server\AbstractController
{
    public function main()
    {
        header('HTTP/1.1 404 NOT FOUND');
        echo 'FILE NOT FOUND';
    }
}
