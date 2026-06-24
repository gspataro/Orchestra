<?php

namespace Orchestra\Rehearsal;

use Orchestra\Compiler\BuildContextProvider;
use Orchestra\Page\Page;
use Orchestra\Page\PageCollection;
use Orchestra\Publisher\BuilderInterface;
use Orchestra\Theme\ThemeProvider;

final class Router
{
    public function __construct(
        private readonly BuildContextProvider $context,
        private readonly PageCollection $pages
    ) {
    }

    public function handleRequest(string $uri): Response
    {
        $path = parse_url($uri, PHP_URL_PATH);
        $request = trim($path, '/') ?: 'index';

        $file = $this->context->get()->paths()->output($request);

        if (is_file($file)) {
            return $this->handleFile($file);
        }

        return $this->handlePage($request);
    }

    public function handleFile(string $file): Response
    {
        $contentType = match (pathinfo($file, PATHINFO_EXTENSION)) {
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
            default => function_exists('mime_content_type')
                ? (mime_content_type($file) ?: 'application/octet-stream')
                : 'application/octet-stream'
        };

        return $this->prepareResponse(
            ResponseType::FILE,
            [
                'Content-Length' => filesize($file),
                'Content-Type' => $contentType
            ],
            $file
        );
    }

    private function handlePage(string $request): Response
    {
        $page = $this->pages->get('/' . $request);

        if (!$page) {
            $page = $this->pages->get('/' . $request . '/index');
        }

        return $this->prepareResponse(
            ResponseType::PAGE,
            [
                'Status' => $page ? '200 OK' : '404 Not Found'
            ],
            $page ?? $this->pages->get('/404') ?? null
        );
    }

    /**
     * @param ResponseType $type
     * @param array<string, mixed> $headers
     * @param Page|string|null $payload
     * @return Response
     */
    private function prepareResponse(
        ResponseType $type,
        array $headers,
        Page|string|null $payload
    ): Response {
        return new Response($type, $headers, $payload);
    }
}
