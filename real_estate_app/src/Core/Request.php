<?php
declare(strict_types=1);

namespace App\Core;

class Request
{
    private array $server;
    private array $get;
    private array $post;
    private array $files;
    private ?string $body;

    public function __construct()
    {
        $this->server = $_SERVER;
        $this->get = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
        $this->body = file_get_contents('php://input');
    }

    public function getMethod(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    public function getUri(): string
    {
        return $this->server['REQUEST_URI'] ?? '/';
    }

    public function getPath(): string
    {
        return parse_url($this->getUri(), PHP_URL_PATH) ?? '/';
    }

    public function getQuery(): array
    {
        return $this->get;
    }

    public function get(string $key, $default = null)
    {
        return $this->get[$key] ?? $default;
    }

    public function post(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->post;
        }
        return $this->post[$key] ?? $default;
    }

    public function input(string $key = null, $default = null)
    {
        $data = $this->getAllData();
        
        if ($key === null) {
            return $data;
        }
        
        return $data[$key] ?? $default;
    }

    public function getAllData(): array
    {
        $data = array_merge($this->get, $this->post);
        
        // Si es JSON, decodificar
        if ($this->isJson()) {
            $jsonData = json_decode($this->body, true);
            if (is_array($jsonData)) {
                $data = array_merge($data, $jsonData);
            }
        }
        
        return $data;
    }

    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    public function hasFile(string $key): bool
    {
        return isset($this->files[$key]) && $this->files[$key]['error'] === UPLOAD_ERR_OK;
    }

    public function getHeader(string $name): ?string
    {
        $headerName = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        return $this->server[$headerName] ?? null;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function isJson(): bool
    {
        return $this->getHeader('Content-Type') === 'application/json';
    }

    public function isAjax(): bool
    {
        return $this->getHeader('X-Requested-With') === 'XMLHttpRequest';
    }

    public function getIp(): string
    {
        return $this->server['HTTP_X_FORWARDED_FOR'] 
            ?? $this->server['HTTP_X_REAL_IP'] 
            ?? $this->server['REMOTE_ADDR'] 
            ?? '0.0.0.0';
    }

    public function getUserAgent(): string
    {
        return $this->server['HTTP_USER_AGENT'] ?? '';
    }
}