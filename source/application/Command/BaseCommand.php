<?php

namespace GSpataro\Application\Command;

use GSpataro\Application\Process\Process;
use GSpataro\CLI\Command;
use GSpataro\DependencyInjection\Container;

class BaseCommand extends Command
{
    public function __construct(
        protected Container $app
    ) {
    }

    protected function runProcess(Process $process): mixed
    {
        return $process->run(
            $this->input,
            $this->output
        );
    }
}
