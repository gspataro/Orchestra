<?php

namespace Orchestra\Infrastructure;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;
use NunoMaduro\Collision\Provider;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class ExceptionHandlerComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('exception.provider', function ($c, $a): object {
            if (php_sapi_name() === 'cli-server') {
                return new Run();
            } else {
                return new Provider();
            }
        });
    }

    public function boot(Container $container): void
    {
        $provider = $container->get('exception.provider');

        if (php_sapi_name() === 'cli-server') {
            $provider->pushHandler(new PrettyPageHandler());
        }

        $provider->register();
    }
}
