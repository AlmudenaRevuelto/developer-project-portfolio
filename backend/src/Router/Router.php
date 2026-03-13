<?php

namespace Backend\Router;

class Router
{
    private array $routes = [];

    public function add(string $method, string $pattern, callable $handler): void
    {
        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'handler' => $handler
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        foreach ($this->routes as $route) {

            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = "#^" . preg_replace('#\{[a-z]+\}#', '([0-9]+)', $route['pattern']) . "$#";

            if (preg_match($pattern, $uri, $matches)) {

                array_shift($matches);

                call_user_func_array($route['handler'], $matches);
                return;
            }
        }

        http_response_code(404);
        echo json_encode([
            'error' => 'Route not found',
            'path' => $uri,
            'method' => $method
        ]);
    }
}