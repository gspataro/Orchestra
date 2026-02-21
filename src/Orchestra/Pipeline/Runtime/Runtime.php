<?php

namespace Orchestra\Pipeline\Runtime;

use GSpataro\DependencyInjection\Container;
use Orchestra\Pipeline\BuildContext;
use Orchestra\Pipeline\BuildOptions;
use Orchestra\Pipeline\BuildOutputInterface;

abstract class Runtime
{
    public function __construct(
        protected readonly Container $container,
        protected readonly BuildContext $context,
        protected readonly BuildOutputInterface $output
    ) {
    }

    abstract public function run(BuildOptions $options): bool;
}
