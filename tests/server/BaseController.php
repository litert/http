<?php
declare (strict_types = 1);

namespace Test\Server\Controller;

use L\Http\Server as server;
use L\Http\Server\IContext;

abstract class BaseController extends server\AbstractController
{
    use server\TAnnotationController;

    public function __construct(IContext $context)
    {
        parent::__construct($context);

        $this->_initializeAnnotations();
    }

    protected static function __annotationsRouter(
        server\IRouter $router,
        string $name,
        string $entryName,
        $args
    )
    {
        switch ($name) {

        case 'user.requireLogin':

            if (($args[0][0] ?? true) != 'false') {

                $router->hook(
                    'before-request',
                    static::class,
                    'validate',
                    'main',
                    $args
                );
            }

            break;

        case 'user.verifyPrivileges':

            if ($args ?? false) {

                $router->hook(
                    'before-request',
                    static::class,
                    'verifyPrivileges',
                    'main',
                    array_merge(...array_map(function($v): array {

                        if (is_string($v)) { return [$v]; }
                        elseif (is_bool($v)) { return []; }
                        else return $v;

                    }, $args))
                );
            }

            break;
        }
    }

    public function validate($args)
    {
        $this->response->writeLine('Test');

        if (mt_rand(0, 100) > 50) {

            return false;
        }
    }

    public function verifyPrivileges($privileges)
    {
        foreach ($privileges as $priv) {

            $this->response->writeLine("Verified {$priv}");
        }
    }
}
