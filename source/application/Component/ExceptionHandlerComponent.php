<?php

namespace GSpataro\Application\Component;

use GSpataro\DependencyInjection\Component;
use NunoMaduro\Collision\Provider;

class ExceptionHandlerComponent extends Component
{
    public function register(): void
    {
        $this->container->add('exception.provider', function ($container, $args): object {
            return new Provider();
        });
    }

    public function boot(): void
    {
        $provider = $this->container->get('exception.provider');
        $provider->register();
    }
}
