<?php

namespace Orchestra\Pages;

use GSpataro\DependencyInjection\Container;
use Orchestra\Pages\PageCollection;
use Orchestra\Pages\GeneratorsCollection;
use Orchestra\Pages\Generator\OnceGenerator;
use Orchestra\Pages\Generator\ArchiveGenerator;
use Orchestra\Pages\Generator\LoopGenerator;
use Orchestra\Application\Component;
use Orchestra\Pages\Generator\CollectionGenerator;

final class PagesComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('pages.collection', function ($container, $args): object {
            return new PageCollection();
        });

        $container->add('pages.generators', function ($container, $args): object {
            return new GeneratorsCollection();
        });
    }

    public function boot(Container $container): void
    {
        $generatorsCollection = $container->get('pages.generators');

        $generatorsCollection->add('once', new OnceGenerator(
            $container->get('pages.collection'),
            $container->get('project.sitemap')
        ));

        $generatorsCollection->add('loop', new LoopGenerator(
            $container->get('pages.collection'),
            $container->get('project.sitemap')
        ));

        $generatorsCollection->add('archive', new ArchiveGenerator(
            $container->get('pages.collection'),
            $container->get('project.sitemap')
        ));

        $generatorsCollection->add('collection', new CollectionGenerator(
            $container->get('pages.collection'),
            $container->get('project.sitemap')
        ));
    }
}
