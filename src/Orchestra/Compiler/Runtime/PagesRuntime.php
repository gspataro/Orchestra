<?php

namespace Orchestra\Compiler\Runtime;

use Orchestra\Publisher\BuilderCollection;
use Orchestra\Page\PageCollection;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Publisher\Publisher;

final class PagesRuntime extends Runtime
{
    private readonly PageCollection $pages;
    private readonly BuilderCollection $builders;
    private readonly Publisher $publisher;

    public function run(BuildOptions $options): bool
    {
        if ($options->cleanupOnly) {
            $this->output->warning('Skipping pages build');
            return true;
        }

        $this->output->info('Building pages');

        $this->pages = $this->container->get('pages.collection');
        $this->builders = $this->container->get('publisher.builders');
        $this->publisher = $this->container->get('publisher');

        foreach ($this->pages as $page) {
            $builder = $this->builders->get($page->schema->builder);
            $output = $builder->compile($page);

            $this->publisher->publish($page->permalink, $output);
        }

        return true;
    }
}
