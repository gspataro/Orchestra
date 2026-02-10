<?php

namespace Orchestra\Console\Runtime;

use GSpataro\CLI\Interface\InputInterface;
use GSpataro\CLI\Interface\OutputInterface;
use GSpataro\DependencyInjection\Container;
use Orchestra\Pipeline\BuildContext;

abstract class Runtime
{
    protected InputInterface $input;
    protected OutputInterface $output;

    abstract protected function main(): mixed;

    public function __construct(
        protected readonly Container $container,
        protected readonly BuildContext $context
    ) {
    }

    public function run(InputInterface $input, OutputInterface $output): mixed
    {
        $this->input = $input;
        $this->output = $output;

        return $this->main();
    }
}
