<?php

namespace Orchestra\Page;

use GSpataro\DependencyInjection\Container;
use Orchestra\Page\PageCollection;
use Orchestra\Page\GeneratorCollection;
use Orchestra\Page\Generator\OnceGenerator;
use Orchestra\Page\Generator\ArchiveGenerator;
use Orchestra\Page\Generator\LoopGenerator;
use Orchestra\Application\Component;
use Orchestra\Page\Factory\PageFactory;
use Orchestra\Page\Factory\SchemaFactory;
use Orchestra\Page\Generator\CollectionGenerator;

final class PagesComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('pages.collection', function ($c, $a): object {
            return new PageCollection();
        });

        $container->add('pages.generators', function ($c, $a): object {
            return new GeneratorCollection();
        });

        $container->add('pages.factory', function ($c, $a): object {
            return new PageFactory(
                $c->get('project.sitemap')
            );
        });

        $container->add('pages.schema.factory', function ($c, $a): object {
            return new SchemaFactory();
        });
    }

    public function boot(Container $container): void
    {
        $generatorsCollection = $container->get('pages.generators');

        $generatorsCollection->add('once', new OnceGenerator(
            $container->get('project.sitemap')
        ));

        $generatorsCollection->add('loop', new LoopGenerator(
            $container->get('project.sitemap')
        ));

        $generatorsCollection->add('archive', new ArchiveGenerator(
            $container->get('project.sitemap')
        ));

        $generatorsCollection->add('collection', new CollectionGenerator(
            $container->get('project.sitemap')
        ));
    }
}
