<?php

namespace Orchestra\Solista;

use GSpataro\DependencyInjection\Container;

abstract class Component
{
    abstract public function register(Container $container): void;
    abstract public function boot(Container $container): void;
}
