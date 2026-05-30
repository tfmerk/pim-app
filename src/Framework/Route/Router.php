<?php

declare(strict_types=1);

namespace tfmerk\PolarisPim\Framework\Route;

use ReflectionClass;
use tfmerk\PolarisPim\Framework\Request\Request;

class Router
{
	protected array $routes = [];

	public function registerControllers(array $controllers): void
	{
		foreach ($controllers as $controller) {
			$reflectionClass = new ReflectionClass($controller);
			$reflectionMethods = $reflectionClass->getMethods();

			foreach ($reflectionMethods as $method) {
				$attributes = $method->getAttributes(Route::class);

				foreach ($attributes as $attribute) {
					/** @var Route $routeInstance */
					$routeInstance = $attribute->newInstance();

					$this->routes[$routeInstance->method][$routeInstance->path] = [
						'controller' => $controller,
						'action' => $method->getName(),
					];
				}
			}
		}
	}

	public function dispatch(Request $request): void
	{
		$method = $request->method;
		$uri = $request->uri;

		if (isset($this->routes[$method][$uri])) {
			$route = $this->routes[$method][$uri];
			$controllerName = $route['controller'];
			$action = $route['action'];

			$controller = new $controllerName($request);
			echo $controller->$action();
			return;
		}

		http_response_code(404);
		echo '404 - Page not found!';
	}
}
