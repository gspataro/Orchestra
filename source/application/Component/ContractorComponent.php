<?php

namespace GSpataro\Application\Component;

use GSpataro\Contractor\Builder\ArchiveBuilder;
use GSpataro\Contractor\BuildersCollection;
use GSpataro\Contractor\Builder\PostBuilder;
use GSpataro\Contractor\Builder\SimpleBuilder;
use GSpataro\DependencyInjection\Container;
use GSpataro\Solista\Component;

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

        $buildersCollection->add('simple', new SimpleBuilder(
            $container->get('twig')
        ));

        $buildersCollection->add('post', new PostBuilder(
            $container->get('twig')
        ));

        $buildersCollection->add('archive', new ArchiveBuilder(
            $container->get('twig')
        ));
    }
}
