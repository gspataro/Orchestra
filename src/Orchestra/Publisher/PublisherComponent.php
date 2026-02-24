<?php

namespace Orchestra\Publisher;

use Orchestra\Publisher\BuilderCollection;
use Orchestra\Publisher\Builder\TwigBuilder;
use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;

final class PublisherComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('publisher.builders', function ($c, $a): object {
            return new BuilderCollection();
        });

        $container->add('publisher', function ($c, $a): object {
            return new Publisher(
                $c->get('pipeline.context')
            );
        });
    }

    public function boot(Container $container): void
    {
        /** @var BuilderCollection */
        $buildersCollection = $container->get('publisher.builders');

        $buildersCollection->add('twig', new TwigBuilder(
            $container->get('twig')
        ));
    }
}
