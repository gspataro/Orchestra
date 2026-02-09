<?php

namespace Orchestra\Console\Runtime;

use Orchestra\Contractor\BuildersCollection;
use Orchestra\Pages\Pages;

final class RuntimePages extends Runtime
{
    private readonly Pages $pages;
    private readonly BuildersCollection $builders;

    protected function main(): mixed
    {
        $this->pages = $this->container->get('pages.collection');
        $this->builders = $this->container->get('contractor.builders');

        $this->output->print('{bold}Building pages');

        foreach ($this->pages->getAll() as $page) {
            $builder = $this->builders->get($page['builder']);
            $builder->compile($page);
        }

        return true;
    }
}
