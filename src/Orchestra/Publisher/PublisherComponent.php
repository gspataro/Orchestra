<?php

namespace Orchestra\Publisher;

use Orchestra\Publisher\Adapter\FilesystemAdapter;
use Orchestra\Publisher\Adapter\HttpAdapter;
use Orchestra\Publisher\Builder\TwigBuilder;
use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;
use Orchestra\Publisher\Strategy\HtmlOutputStrategy;
use Orchestra\Publisher\Strategy\PrettyOutputStrategy;

final class PublisherComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('publisher.adapter', function ($c, $a): object {
            $adapter = php_sapi_name() === 'cli-server'
                ? new HttpAdapter()
                : new FilesystemAdapter(
                    $c->get('compiler.context.provider')
                );

            return $adapter;
        });

        $container->add('publisher.builder', function ($c, $a): object {
            return new TwigBuilder(
                $c->get('twig')
            );
        });

        $container->add('publisher', function ($c, $a): object {
            return new Publisher(
                $c->get('publisher.adapter')
            );
        });

        $container->add('publisher.registry', function ($c, $a): object {
            return new OutputRegistry();
        });

        $container->add('publisher.strategies', function ($c, $a): object {
            return new OutputStrategyCollection();
        });
    }

    public function boot(Container $container): void
    {
        /** @var OutputStrategyCollection */
        $strategiesCollection = $container->get('publisher.strategies');

        $strategiesCollection->add('html', new HtmlOutputStrategy());
        $strategiesCollection->add('pretty', new PrettyOutputStrategy());
    }
}
