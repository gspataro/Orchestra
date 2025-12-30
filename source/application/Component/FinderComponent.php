<?php

namespace GSpataro\Application\Component;

use GSpataro\DependencyInjection\Component;
use GSpataro\Finder\Researcher;

final class FinderComponent extends Component
{
    public function register(): void
    {
        $this->container->add('finder.researcher', function ($container, $args): object {
            return new Researcher(
                $container->get('library.archive')
            );
        });
    }

    public function boot(): void
    {
    }
}
