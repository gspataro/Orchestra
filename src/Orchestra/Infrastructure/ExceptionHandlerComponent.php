<?php

namespace Orchestra\Infrastructure;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;
use NunoMaduro\Collision\Provider;

class ExceptionHandlerComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('exception.provider', function ($c, $a): object {
            return new Provider();
        });
    }

    public function boot(Container $container): void
    {
        $provider = $container->get('exception.provider');
        $provider->register();
    }
}
