<?php

namespace Orchestra\Solista\Component;

use Dotenv\Dotenv;
use GSpataro\DependencyInjection\Container;
use Orchestra\Solista\Component;

class DotenvComponent extends Component
{
    public function register(Container $container): void
    {
    }

    public function boot(Container $container): void
    {
        $dotenv = Dotenv::createImmutable(DIR_ROOT);
        $dotenv->load();
    }
}
