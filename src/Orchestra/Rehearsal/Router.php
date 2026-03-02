<?php

namespace Orchestra\Rehearsal;

use Orchestra\Compiler\BuildContext;

final class Router
{
    public function __construct(
        private readonly BuildContext $context
    ) {
    }

    public function handleRequest(array $server): void
    {
        $uri = parse_url($server['REQUEST_URI'], PHP_URL_PATH);
        $uri = trim($uri, '/');

        if ($uri === '') {
            $uri = 'index';
        }

        $this->serveResource($uri);
    }

    private function serveResource(string $uri): void
    {
        $file = $this->context->paths()->output($uri);

        if (is_file($file)) {
            $this->serveFile($file);
            return;
        }

        if (!is_file($file . '.html')) {
            $file = pathJoin($file, 'index');
        }

        if (is_file($file . '.html')) {
            $this->serveFile($file . '.html');
            return;
        }

        $this->serve404();
    }

    private function serveFile(string $filePath): void
    {
        $contentType = match (pathinfo($filePath, PATHINFO_EXTENSION)) {
            'html' => 'text/html',
            'css'  => 'text/css',
            'js'   => 'application/javascript',
            'json' => 'application/json',
            'png'  => 'image/png',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif'  => 'image/gif',
            'svg'  => 'image/svg+xml',
            'woff' => 'font/woff',
            'woff2'=> 'font/woff2',
            'ttf'  => 'font/ttf',
            default => mime_content_type($filePath) ?: 'application/octet-stream'
        };

        header('Content-Type: ' . $contentType);
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);
    }

    private function serve404(): void
    {
        http_response_code(404);

        $file = $this->context->paths()->output('404.html');

        if (is_file($file)) {
            header('Content-Type: text/html');
            readfile($file);
            return;
        }

        echo "404 Not Found";
    }
}
