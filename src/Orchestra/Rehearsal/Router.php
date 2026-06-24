<?php

namespace Orchestra\Rehearsal;

use Orchestra\Compiler\BuildContextProvider;
use Orchestra\Page\PageCollection;
use Orchestra\Publisher\BuilderInterface;
use Orchestra\Theme\ThemeProvider;

final class Router
{
    public function __construct(
        private readonly BuildContextProvider $context,
        private readonly PageCollection $pages,
        private readonly BuilderInterface $builder,
        private readonly ThemeProvider $themeProvider
    ) {
    }

    public function handleRequest(string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH);
        $request = trim($path, '/') ?: 'index';

        $this->serveResource($request);
    }

    private function serveResource(string $uri): void
    {
        $file = $this->context->get()->paths()->output($uri);

        if (is_file($file)) {
            $this->serveFile($file);
            return;
        }

        if (str_starts_with($uri, 'assets')) {
            $theme = $this->themeProvider->get();
            $asset = pathJoin($theme->path, $theme->assets->dir, substr($uri, strlen('assets/')));

            if (is_file($asset)) {
                $this->serveFile($asset);
                return;
            }
        }

        $page = $this->pages->get('/' . $uri);

        if (!$page) {
            $page = $this->pages->get('/' . $uri . '/index');
        }

        if ($page) {
            echo $this->builder->compile($page);
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

        $page = $this->pages->get('/404');

        if ($page) {
            echo $this->builder->compile($page);
            return;
        }

        echo "404 Not Found";
    }
}
