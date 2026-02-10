<?php

namespace Orchestra\Console\Runtime;

use Orchestra\Contractor\BuildersCollection;
use Orchestra\Pages\Page\PageCollection;

final class PagesRuntime extends Runtime
{
    private readonly PageCollection $pages;
    private readonly BuildersCollection $builders;

    protected function main(): mixed
    {
        $this->pages = $this->container->get('pages.collection');
        $this->builders = $this->container->get('contractor.builders');

        $this->output->print('{bold}Building pages');

        foreach ($this->pages as $page) {
            $builder = $this->builders->get($page->schema->builder);
            $builder->compile($page);
        }

        return true;
    }
}
