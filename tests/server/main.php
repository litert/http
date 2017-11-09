<?php
declare (strict_types = 1);

define('DEBUG', 1);

require 'autoload.php';

use L\Http\Server\SimpleFactory;

(function() {

    header('Content-Type: text/plain');
    error_reporting(E_ALL);
    ini_set('display_errors', 'off');

    \L\Core\boot();

    \L\Core\EventBus::getInstance()->on('error', function($error) {

        print_r($error);
    });

    $serverFactory = new SimpleFactory();

    $createRouter = require ('router.php');

    $router = $createRouter($serverFactory);

    $context = $serverFactory->createContext();

    $context->router = $router;

    $server = $serverFactory->createServer($context);

    $server->handle();

})();
