<?php

namespace Orchestra\Finder;

use GSpataro\DependencyInjection\Container;
use Orchestra\Finder\Researcher;
use Orchestra\Solista\Component;

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
