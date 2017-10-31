<?php
declare (strict_types = 1);

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/BaseController.php';
spl_autoload_register(function ($name) {

    if (substr($name, 0, 23) === 'Test\Server\Controller\\') {

        $class = substr($name, 23);
        require __DIR__ . "/controllers/{$class}.php";
    }
    elseif (substr($name, 0, 12) === 'Test\Server\\') {

        $class = substr($name, 12);
        require __DIR__ . "/{$class}.php";
    }
});

