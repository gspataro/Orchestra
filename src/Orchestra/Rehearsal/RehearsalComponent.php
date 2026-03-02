<?php

namespace Orchestra\Rehearsal;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;

final class RehearsalComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('rehearsal.router', function ($c, $a): object {
            return new Router();
        });
    }

    public function boot(Container $container): void
    {
    }
}
