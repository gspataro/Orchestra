<?php

namespace Orchestra\Infrastructure;

use Dotenv\Dotenv;
use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;

class DotenvComponent extends Component
{
    public function register(Container $container): void
    {
    }

    public function boot(Container $container): void
    {
        /** @var \Orchestra\Pipeline\BuildContext */
        $context = $container->get('compiler.context');

        $dotenv = Dotenv::createImmutable($context->paths->root());
        $dotenv->safeLoad();
    }
}
