<?php

namespace Orchestra\Compiler\Runtime;

use GSpataro\DependencyInjection\Container;
use Orchestra\Compiler\BuildContext;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Compiler\BuildOutputInterface;
use Orchestra\Compiler\RuntimeInterface;

abstract class Runtime implements RuntimeInterface
{
    public function __construct(
        protected readonly Container $container,
        protected readonly BuildContext $context,
        protected readonly BuildOutputInterface $output
    ) {
    }

    abstract public function run(BuildOptions $options): bool;
}
