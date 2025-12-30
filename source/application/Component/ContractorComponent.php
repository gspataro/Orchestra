<?php

namespace GSpataro\Application\Component;

use GSpataro\Contractor\Builder\ArchiveBuilder;
use GSpataro\Contractor\BuildersCollection;
use GSpataro\DependencyInjection\Component;
use GSpataro\Contractor\Builder\PostBuilder;
use GSpataro\Contractor\Builder\SimpleBuilder;

final class ContractorComponent extends Component
{
    public function register(): void
    {
        $this->container->add('contractor.builders', function ($container, $args): object {
            return new BuildersCollection();
        });
    }

    public function boot(): void
    {
        $buildersCollection = $this->container->get('contractor.builders');

        $buildersCollection->add('simple', new SimpleBuilder(
            $this->container->get('twig')
        ));

        $buildersCollection->add('post', new PostBuilder(
            $this->container->get('twig')
        ));

        $buildersCollection->add('archive', new ArchiveBuilder(
            $this->container->get('twig')
        ));
    }
}
