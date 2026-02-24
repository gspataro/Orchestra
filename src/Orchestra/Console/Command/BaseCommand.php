<?php

namespace Orchestra\Console\Command;

use Orchestra\Console\Runtime\Runtime;
use GSpataro\CLI\Command;
use GSpataro\DependencyInjection\Container;

class BaseCommand extends Command
{
    public function __construct(
        protected Container $container
    ) {
    }

    protected function runProcess(string|Runtime $process): mixed
    {
        if (!is_object($process)) {
            $process = new $process(
                $this->container,
                $this->container->get('compiler.context')
            );
        }

        return $process->run(
            $this->input,
            $this->output
        );
    }
}
