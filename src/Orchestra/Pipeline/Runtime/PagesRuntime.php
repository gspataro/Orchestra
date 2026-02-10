<?php

namespace Orchestra\Pipeline\Runtime;

use Orchestra\Contractor\BuildersCollection;
use Orchestra\Pages\Page\PageCollection;

final class PagesRuntime extends Runtime
{
    private readonly PageCollection $pages;
    private readonly BuildersCollection $builders;

    public function run(array $options = []): bool
    {
        if ($options['cleanup-only'] !== null) {
            return true;
        }

        $this->output->info('Building pages');

        $this->pages = $this->container->get('pages.collection');
        $this->builders = $this->container->get('contractor.builders');

        foreach ($this->pages as $page) {
            $builder = $this->builders->get($page->schema->builder);
            $builder->compile($page);
        }

        return true;
    }
}
