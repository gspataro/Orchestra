<?php

namespace Orchestra\Sitemap;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;
use Orchestra\Sitemap\Factory\SitemapResourceFactory;

final class SitemapComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('sitemap.permalink', function ($c, $a): object {
            return new Permalink();
        });

        $container->add('sitemap.resource.factory', function ($c, $a): object {
            return new SitemapResourceFactory();
        });

        $container->add('sitemap', function ($c, $a): object {
            return new Sitemap();
        });
    }

    public function boot(Container $container): void
    {
    }
}
