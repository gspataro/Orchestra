<?php

namespace Orchestra\Theme;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;
use Orchestra\Theme\ThemeLoader;

final class ThemeComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('theme.loader', function ($c, $a): object {
            return new ThemeLoader(
                $c->get('compiler.context')
            );
        });
    }

    public function boot(Container $container): void
    {
    }
}
