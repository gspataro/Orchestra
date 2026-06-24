<?php

namespace Orchestra\Compiler\Runtime;

use Orchestra\Page\PageCollection;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Rehearsal\Router;

final class ServeRuntime extends Runtime
{
    private Router $router;

    public function run(BuildOptions $options): bool
    {
        $this->output->info('Serving pages');

        $this->router = $this->container->get('rehearsal.router');
        $this->router->handleRequest($_SERVER['REQUEST_URI'] ?? '');

        return true;
    }
}
