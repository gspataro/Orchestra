<?php

namespace Orchestra\Compiler\Runtime;

use Orchestra\Page\Page;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Publisher\Publisher;
use Orchestra\Rehearsal\ResponseType;
use Orchestra\Rehearsal\Router;

final class ServeRuntime extends Runtime
{
    private Router $router;
    private Publisher $publisher;

    private function serveFile(string $path): void
    {
        readfile($path);
    }

    private function servePage(Page|null $page): void
    {
        if (is_null($page)) {
            echo 'Page not found';
            return;
        }

        $this->publisher->publish($page);
    }

    public function run(BuildOptions $options): bool
    {
        $this->output->info('Serving pages');

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
