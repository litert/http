<?php
declare (strict_types = 1);

namespace Test\Server\Controller;

/**
 * @http.get(path="/users/{id:uint}")
 * @http.post(path="/users/{id:int}")
 */
class User extends BaseController
{
    public function main(int $id)
    {
        echo $id;
    }
}
