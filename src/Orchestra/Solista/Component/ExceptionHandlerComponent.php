<?php

namespace Orchestra\Solista\Component;

use GSpataro\DependencyInjection\Container;
use Orchestra\Solista\Component;
use NunoMaduro\Collision\Provider;

class ExceptionHandlerComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('exception.provider', function ($container, $args): object {
            return new Provider();
        });
    }

    public function boot(Container $container): void
    {
        $provider = $container->get('exception.provider');
        $provider->register();
    }
}
