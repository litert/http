<?php
declare (strict_types = 1);

return function(\L\Http\Server\IFactory $factory) {

    $router = $factory->createRouter();

    if (DEBUG) {

        apcu_clear_cache();
    }

    if (!$router->loadedFromCache()) {

        $router->initialize();

        $dir = opendir(__DIR__ . '/controllers');

        while ($file = readdir($dir)) {

            if ($file[0] === '.' || substr($file, -4) !== '.php') {

                continue;
            }

            call_user_func([
                'Test\Server\Controller\\' . substr($file, 0, -4),
                'autoRoute'
            ], $router);
        }

        closedir($dir);

        $router->saveToCache();
    }

    return $router;

};
