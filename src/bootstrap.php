<?php

declare(strict_types=1);

use tfmerk\PolarisPim\Framework\Request\Request;
use tfmerk\PolarisPim\Framework\Route\Router;
use tfmerk\PolarisPim\Controllers\HomeController;
use tfmerk\PolarisPim\Controllers\ProductController;

require BASE_PATH . '/vendor/autoload.php';

// todo only for dev env
ini_set("display_errors", 1);

// Remove header to obfuscate that we use PHP.
header_remove('X-Powered-By');

$request = new Request();
$router = new Router();

$router->registerControllers(
	[
		HomeController::class,
		ProductController::class,
	]
);

$router->dispatch($request);
