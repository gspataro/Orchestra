<?php

namespace Orchestra\Publisher;

use Orchestra\Publisher\BuildersCollection;
use Orchestra\Publisher\Builder\TwigBuilder;
use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;

final class PublisherComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('publisher.builders', function ($container, $args): object {
            return new BuildersCollection();
        });
    }

    public function boot(Container $container): void
    {
        $buildersCollection = $container->get('publisher.builders');

        $buildersCollection->add('twig', new TwigBuilder(
            $container->get('pipeline.context'),
            $container->get('twig')
        ));
    }
}
