<?php

namespace GSpataro\Application\Component;

use GSpataro\DependencyInjection\Container;
use GSpataro\Finder\Researcher;
use GSpataro\Solista\Component;

final class FinderComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('finder.researcher', function ($container, $args): object {
            return new Researcher(
                $container->get('library.archive')
            );
        });
    }

    public function boot(Container $container): void
    {
    }
}
