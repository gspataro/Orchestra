<?php

namespace Orchestra\Compiler\Runtime;

use Orchestra\Page\Page;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Publisher\BuilderInterface;
use Orchestra\Publisher\Publisher;
use Orchestra\Rehearsal\ResponseType;
use Orchestra\Rehearsal\Router;
use Orchestra\Theme\ThemeProvider;

final class ServeRuntime extends Runtime
{
    private Router $router;
    private BuilderInterface $builder;
    private ThemeProvider $theme;
    private Publisher $publisher;

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

        $this->publisher->publish(
            '',
            $this->builder->compile($page)
        );
    }

    public function run(BuildOptions $options): bool
    {
        $this->output->info('Serving pages');

        $this->theme = $this->container->get('theme.provider');
        $this->builder = $this->container->get('publisher.builder');
        $this->publisher = $this->container->get('publisher');
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
