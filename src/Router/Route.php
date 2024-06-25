<?php
namespace App\Router;

class Route {
    private $path;
    private $method;
    private $callback;
    private $middleware;

    public function __construct($method, $path, $callback, $middleware = []) {
        $this->method = $method;
        $this->path = $path;
        $this->callback = $callback;
        $this->middleware = $middleware;
    }

    public function matches($uri, $requestMethod): bool
    {
        $pattern = $this->convertPathToPattern($this->path);
        return preg_match($pattern, $uri) && $this->method === $requestMethod;
    }

    private function convertPathToPattern($path): string
    {
        $path = preg_quote($path, '/');
        $path = preg_replace('/\{([a-zA-Z0-9_]+)}/', '(?<$1>[^/]+)', $path);

        return '/^' . $path . '$/i';
    }

    public function execute() {
        foreach ($this->middleware as $middleware) {
            $middleware->handle();
        }

        $requestData = $this->method === 'GET' ? $_GET : $_POST;

        return call_user_func($this->callback, $requestData);
    }
}
