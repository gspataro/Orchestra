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
}
