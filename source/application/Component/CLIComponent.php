<?php

namespace GSpataro\Application\Component;

use GSpataro\CLI\Handler;
use GSpataro\Application\Command;
use GSpataro\CLI\Helper\Stopwatch;
use GSpataro\CLI\CommandsCollection;
use GSpataro\DependencyInjection\Component;

final class CLIComponent extends Component
{
    public function register(): void
    {
        $this->container->add('cli.commands', fn(): object => new CommandsCollection());

        $this->container->add('cli', function ($container, $args): object {
            return new Handler(
                $container->get('cli.commands')
            );
        });

        $this->container->add('cli.stopwatch', function ($container, $args): object {
            return new Stopwatch();
        });
    }

    public function boot(): void
    {
        $cli = $this->container->get('cli');
        $commands = $this->container->get('cli.commands');

        $commands->register(
            new Command\BuildCommand($this->container)
        );

        $cli->deploy();
    }
}
