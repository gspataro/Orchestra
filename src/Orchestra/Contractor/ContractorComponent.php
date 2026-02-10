<?php

namespace Orchestra\Contractor;

use Orchestra\Contractor\Builder\ArchiveBuilder;
use Orchestra\Contractor\BuildersCollection;
use Orchestra\Contractor\Builder\PostBuilder;
use Orchestra\Contractor\Builder\TwigBuilder;
use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;

final class ContractorComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('contractor.builders', function ($container, $args): object {
            return new BuildersCollection();
        });
    }

    public function boot(Container $container): void
    {
        $buildersCollection = $container->get('contractor.builders');

        $buildersCollection->add('twig', new TwigBuilder(
            $container->get('pipeline.context'),
            $container->get('twig')
        ));
    }
}
