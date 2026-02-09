<?php

namespace Orchestra\Application\Component;

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
        $context = $container->get('pipeline.context');

        $dotenv = Dotenv::createImmutable($context->root);
        $dotenv->safeLoad();
    }
}
