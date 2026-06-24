<?php

namespace Orchestra\Publisher;

use Orchestra\Publisher\Adapter\FilesystemAdapter;
use Orchestra\Publisher\BuilderCollection;
use Orchestra\Publisher\Builder\TwigBuilder;
use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;
use Orchestra\Publisher\Strategy\HtmlOutputStrategy;
use Orchestra\Publisher\Strategy\PrettyOutputStrategy;

final class PublisherComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('publisher.builders', function ($c, $a): object {
            return new BuilderCollection();
        });

        $container->add('publisher.adapter', function ($c, $a): object {
            return new FilesystemAdapter(
                $c->get('compiler.context.provider')
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
        /** @var BuilderCollection */
        $buildersCollection = $container->get('publisher.builders');

        $buildersCollection->add('twig', new TwigBuilder(
            $container->get('twig')
        ));

        /** @var OutputStrategyCollection */
        $strategiesCollection = $container->get('publisher.strategies');

        $strategiesCollection->add('html', new HtmlOutputStrategy());
        $strategiesCollection->add('pretty', new PrettyOutputStrategy());
    }
}
