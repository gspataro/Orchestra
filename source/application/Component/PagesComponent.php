<?php

namespace GSpataro\Application\Component;

use GSpataro\Pages\Pages;
use GSpataro\Pages\GeneratorsCollection;
use GSpataro\Pages\Generator\OnceGenerator;
use GSpataro\DependencyInjection\Component;
use GSpataro\Pages\Generator\PaginateGenerator;
use GSpataro\Pages\Generator\LoopGenerator;

final class PagesComponent extends Component
{
    public function register(): void
    {
        $this->container->add('pages.collection', function ($container, $args): object {
            return new Pages();
        });

        $this->container->add('pages.generators', function ($container, $args): object {
            return new GeneratorsCollection();
        });
    }

    public function boot(): void
    {
        $generatorsCollection = $this->container->get('pages.generators');

        $generatorsCollection->add('once', new OnceGenerator(
            $this->container->get('pages.collection'),
            $this->container->get('library.archive'),
            $this->container->get('project.sitemap')
        ));

        $generatorsCollection->add('loop', new LoopGenerator(
            $this->container->get('pages.collection'),
            $this->container->get('library.archive'),
            $this->container->get('project.sitemap')
        ));

        $generatorsCollection->add('paginate', new PaginateGenerator(
            $this->container->get('pages.collection'),
            $this->container->get('library.archive'),
            $this->container->get('project.sitemap')
        ));
    }
}
