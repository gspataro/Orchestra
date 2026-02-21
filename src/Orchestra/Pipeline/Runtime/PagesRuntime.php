<?php

namespace Orchestra\Pipeline\Runtime;

use Orchestra\Publisher\BuildersCollection;
use Orchestra\Pages\Page\PageCollection;
use Orchestra\Pipeline\BuildOptions;

final class PagesRuntime extends Runtime
{
    private readonly PageCollection $pages;
    private readonly BuildersCollection $builders;

    public function run(BuildOptions $options): bool
    {
        if ($options->cleanupOnly) {
            $this->output->warning('Skipping pages build');
            return true;
        }

        $this->output->info('Building pages');

        $this->pages = $this->container->get('pages.collection');
        $this->builders = $this->container->get('publisher.builders');

        foreach ($this->pages as $page) {
            $builder = $this->builders->get($page->schema->builder);
            $builder->compile($page);
        }

        return true;
    }
}
