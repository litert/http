<?php
declare (strict_types = 1);

define('DEBUG', 0);

require 'autoload.php';

use L\HTTP\ServerFactory;

$router = require ('router.php');

$context = ServerFactory::createContext();

$context->router = $router;

$server = ServerFactory::createServer($context);

$server->handle();
