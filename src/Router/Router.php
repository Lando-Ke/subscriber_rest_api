<?php
namespace App\Router;

class Router {
    private array $routes = [];

    public function addRoute(Route $route): void
    {
        $this->routes[] = $route;
    }

    public function dispatch($uri, $requestMethod) {
        foreach ($this->routes as $route) {
            if ($route->matches($uri, $requestMethod)) {
                return $route->execute();
            }
        }

        // If no route is matched, send a 404 response
        http_response_code(404);
        echo "Not Found";
        exit;
    }
}
