<?php
declare(strict_types=1);

namespace App\Core;

class Response
{
    private int $statusCode = 200;
    private array $headers = [];
    private string $body = '';

    public function status(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    public function header(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function json(array $data): void
    {
        $this->header('Content-Type', 'application/json');
        $this->body = json_encode($data, JSON_UNESCAPED_UNICODE);
        $this->send();
    }

    public function send(string $content = null): void
    {
        if ($content !== null) {
            $this->body = $content;
        }

        // Enviar status code
        http_response_code($this->statusCode);

        // Enviar headers
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        // Enviar body
        echo $this->body;
    }

    public function redirect(string $url, int $code = 302): void
    {
        $this->status($code);
        $this->header('Location', $url);
        $this->send();
        exit;
    }

    public function view(string $template, array $data = []): void
    {
        $content = $this->renderTemplate($template, $data);
        $this->header('Content-Type', 'text/html');
        $this->send($content);
    }

    private function renderTemplate(string $template, array $data = []): string
    {
        $templatePath = __DIR__ . "/../../views/$template.php";
        
        if (!file_exists($templatePath)) {
            throw new \Exception("Template no encontrado: $template");
        }

        extract($data);
        
        ob_start();
        include $templatePath;
        return ob_get_clean();
    }

    public function download(string $filePath, string $filename = null): void
    {
        if (!file_exists($filePath)) {
            $this->status(404)->send('Archivo no encontrado');
            return;
        }

        $filename = $filename ?: basename($filePath);
        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';

        $this->header('Content-Type', $mimeType);
        $this->header('Content-Disposition', "attachment; filename=\"$filename\"");
        $this->header('Content-Length', (string)filesize($filePath));
        
        $this->send();
        readfile($filePath);
    }
}