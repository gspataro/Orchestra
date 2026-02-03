<?php

namespace GSpataro\Application\Component;

use Dotenv\Dotenv;
use GSpataro\DependencyInjection\Container;
use GSpataro\Solista\Component;

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
