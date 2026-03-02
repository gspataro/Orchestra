<?php

namespace Orchestra\Compiler\Runtime;

use Orchestra\Page\PageCollection;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Rehearsal\Router;

final class ServeRuntime extends Runtime
{
    private PageCollection $pages;
    private Router $router;

    public function run(BuildOptions $options): bool
    {
        if ($options->cleanupOnly) {
            $this->output->warning('Skipping pages build');
            return true;
        }

        $this->output->info('Building pages');

        $this->pages = $this->container->get('pages.collection');
        $this->router = $this->container->get('rehearsal.router');

        $this->router->handleRequest($_SERVER);

        return true;
    }
}
