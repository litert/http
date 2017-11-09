<?php
declare (strict_types = 1);

namespace Test\Server\Controller;

/**
 * Class TestHandler
 *
 * @package litert/http
 *
 * @http.get("/test")
 *
 * @user.requireLogin(false)
 *
 * @user.verifyPrivileges(
 *   create_user,
 *   delete_user,
 *   watch_video,
 *   login
 * )
 */
class Test extends BaseController
{
    public function main()
    {
        echo 'Hello world';
    }
}
