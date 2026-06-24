<?php

namespace Orchestra\Compiler\Runtime;

use Orchestra\Page\Page;
use Orchestra\Page\PageCollection;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Publisher\BuilderInterface;
use Orchestra\Rehearsal\Response;
use Orchestra\Rehearsal\ResponseType;
use Orchestra\Rehearsal\Router;
use Orchestra\Theme\ThemeProvider;

final class ServeRuntime extends Runtime
{
    private Router $router;
    private BuilderInterface $builder;
    private ThemeProvider $theme;

    private function serveFile(string $path): void
    {
        if (str_starts_with($path, 'assets')) {
            $theme = $this->theme->get();
            $asset = pathJoin($theme->path, $theme->assets->dir, substr($path, strlen('assets/')));

            if (is_file($asset)) {
                readfile($asset);
                return;
            }
        }

        readfile($path);
    }

    private function servePage(Page|null $page): void
    {
        if (is_null($page)) {
            echo 'Page not found';
            return;
        }

        echo $this->builder->compile($page);
    }

    public function run(BuildOptions $options): bool
    {
        $this->output->info('Serving pages');

        $this->theme = $this->container->get('theme.provider');
        $this->builder = $this->container->get('publisher.builder');
        $this->router = $this->container->get('rehearsal.router');

        $response = $this->router->handleRequest($_SERVER['REQUEST_URI'] ?? '');

        foreach ($response->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        if ($response->type === ResponseType::FILE) {
            $this->serveFile($response->payload);
            return true;
        }

        $this->servePage($response->payload);

        return true;
    }
}
