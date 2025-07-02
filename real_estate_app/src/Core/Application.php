<?php
declare(strict_types=1);

namespace App\Core;

use App\Core\Router;
use App\Core\Request;
use App\Core\Response;
use App\Database\Database;

class Application
{
    private Router $router;
    private Request $request;
    private Response $response;
    private array $middleware = [];

    public function __construct()
    {
        $this->router = new Router();
        $this->request = new Request();
        $this->response = new Response();
    }

    public function use($middleware): void
    {
        $this->middleware[] = $middleware;
    }

    public function get(string $path, $handler): void
    {
        $this->router->get($path, $handler);
    }

    public function post(string $path, $handler): void
    {
        $this->router->post($path, $handler);
    }

    public function put(string $path, $handler): void
    {
        $this->router->put($path, $handler);
    }

    public function delete(string $path, $handler): void
    {
        $this->router->delete($path, $handler);
    }

    public function group(string $prefix, callable $callback, array $middleware = []): void
    {
        $this->router->group($prefix, $callback, $middleware);
    }

    public function run(): void
    {
        try {
            // Ejecutar middleware global
            foreach ($this->middleware as $middleware) {
                if (!$middleware->handle($this->request, $this->response)) {
                    return;
                }
            }

            // Procesar ruta
            $result = $this->router->dispatch($this->request);
            
            if ($result === null) {
                $this->response->status(404);
                $this->response->json(['error' => 'Ruta no encontrada']);
                return;
            }

            // Si el resultado es un array, enviarlo como JSON
            if (is_array($result)) {
                $this->response->json($result);
            } elseif (is_string($result)) {
                $this->response->send($result);
            }

        } catch (\Throwable $e) {
            $this->handleException($e);
        }
    }

    private function handleException(\Throwable $e): void
    {
        error_log("Application Error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
        
        $this->response->status(500);
        
        if ($_ENV['APP_DEBUG'] === 'true') {
            $this->response->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
        } else {
            $this->response->json(['error' => 'Error interno del servidor']);
        }
    }
}