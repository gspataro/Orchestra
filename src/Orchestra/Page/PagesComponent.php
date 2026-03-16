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
                $c->get('sitemap'),
                $c->get('sitemap.permalink'),
                $c->get('sitemap.resource.factory')
            );
        });

        $container->add('pages.schema.factory', function ($c, $a): object {
            return new SchemaFactory();
        });
    }

    public function boot(Container $container): void
    {
        $generatorsCollection = $container->get('pages.generators');

        $generatorsCollection->add('once', new OnceGenerator());
        $generatorsCollection->add('loop', new LoopGenerator());
        $generatorsCollection->add('archive', new ArchiveGenerator());
        $generatorsCollection->add('collection', new CollectionGenerator());
    }
}
