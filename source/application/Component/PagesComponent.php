<?php

namespace GSpataro\Application\Component;

use GSpataro\DependencyInjection\Container;
use GSpataro\Pages\Pages;
use GSpataro\Pages\GeneratorsCollection;
use GSpataro\Pages\Generator\OnceGenerator;
use GSpataro\Pages\Generator\PaginateGenerator;
use GSpataro\Pages\Generator\LoopGenerator;
use GSpataro\Solista\Component;

final class PagesComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('pages.collection', function ($container, $args): object {
            return new Pages();
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
            $container->get('library.archive'),
            $container->get('project.sitemap')
        ));

        $generatorsCollection->add('loop', new LoopGenerator(
            $container->get('pages.collection'),
            $container->get('library.archive'),
            $container->get('project.sitemap')
        ));

        $generatorsCollection->add('paginate', new PaginateGenerator(
            $container->get('pages.collection'),
            $container->get('library.archive'),
            $container->get('project.sitemap')
        ));
    }
}
