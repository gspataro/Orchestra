<?php

namespace Orchestra\Blueprint;

use GSpataro\DependencyInjection\Container;
use Orchestra\Blueprint\Blueprint;
use Orchestra\Application\Component;

final class BlueprintComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('blueprint', function ($c, $a): object {
            return new Blueprint();
        });

        $container->add('blueprint.compiler', function ($c, $a): object {
            return new BlueprintCompiler();
        });
    }

    public function boot(Container $container): void
    {
    }
}
