<?php
declare (strict_types = 1);

namespace Test\Server\Controller;

use L\Http\Server as server;

/**
 * @http.get(path="/users/{id:uint}")
 * @http.post(path="/users/{id:int}")
 */
class User extends server\AbstractController
{
    public function main(int $id)
    {
        echo $id;
    }
}
