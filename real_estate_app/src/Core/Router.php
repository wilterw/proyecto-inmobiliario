<?php
declare(strict_types=1);

namespace App\Core;

use App\Core\Request;

class Router
{
    private array $routes = [];
    private array $groups = [];

    public function get(string $path, $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function put(string $path, $handler): void
    {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete(string $path, $handler): void
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    public function group(string $prefix, callable $callback, array $middleware = []): void
    {
        $previousPrefix = $this->getCurrentPrefix();
        $previousMiddleware = $this->getCurrentMiddleware();
        
        $this->groups[] = [
            'prefix' => $previousPrefix . $prefix,
            'middleware' => array_merge($previousMiddleware, $middleware)
        ];

        $callback($this);

        array_pop($this->groups);
    }

    private function addRoute(string $method, string $path, $handler): void
    {
        $prefix = $this->getCurrentPrefix();
        $middleware = $this->getCurrentMiddleware();
        
        $fullPath = $prefix . $path;
        
        $this->routes[] = [
            'method' => $method,
            'path' => $fullPath,
            'handler' => $handler,
            'middleware' => $middleware,
            'pattern' => $this->pathToPattern($fullPath)
        ];
    }

    private function getCurrentPrefix(): string
    {
        return end($this->groups)['prefix'] ?? '';
    }

    private function getCurrentMiddleware(): array
    {
        return end($this->groups)['middleware'] ?? [];
    }

    private function pathToPattern(string $path): string
    {
        return '#^' . preg_replace('/\{([^}]+)\}/', '([^/]+)', $path) . '$#';
    }

    public function dispatch(Request $request)
    {
        $method = $request->getMethod();
        $uri = parse_url($request->getUri(), PHP_URL_PATH);
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches); // Remover la coincidencia completa
                
                // Ejecutar middleware de la ruta
                foreach ($route['middleware'] as $middleware) {
                    if (!$middleware->handle($request, new Response())) {
                        return null;
                    }
                }

                return $this->executeHandler($route['handler'], $matches, $request);
            }
        }

        return null;
    }

    private function executeHandler($handler, array $params, Request $request)
    {
        if (is_array($handler)) {
            [$controller, $method] = $handler;
            $controllerInstance = new $controller();
            return $controllerInstance->$method($request, ...$params);
        }

        if (is_callable($handler)) {
            return $handler($request, ...$params);
        }

        throw new \InvalidArgumentException('Handler no válido');
    }
}