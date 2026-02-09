<?php

namespace Orchestra\Console\Command;

use Orchestra\Console\Runtime\Runtime;
use GSpataro\CLI\Command;
use GSpataro\DependencyInjection\Container;

class BaseCommand extends Command
{
    public function __construct(
        protected Container $app
    ) {
    }

    protected function runProcess(Runtime $process): mixed
    {
        return $process->run(
            $this->input,
            $this->output
        );
    }
}
