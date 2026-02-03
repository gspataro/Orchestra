<?php

namespace GSpataro\Application\Component;

use GSpataro\CLI\Handler;
use GSpataro\Application\Command;
use GSpataro\CLI\Helper\Stopwatch;
use GSpataro\CLI\CommandsCollection;
use GSpataro\DependencyInjection\Container;
use GSpataro\Solista\Component as SolistaComponent;

final class CLIComponent extends SolistaComponent
{
    public function register(Container $container): void
    {
        $container->add('cli.commands', fn(): object => new CommandsCollection());

        $container->add('cli', function ($container, $args): object {
            return new Handler(
                $container->get('cli.commands')
            );
        });

        $container->add('cli.stopwatch', function ($container, $args): object {
            return new Stopwatch();
        });
    }

    public function boot(Container $container): void
    {
        $cli = $container->get('cli');
        $commands = $container->get('cli.commands');

        $commands->register(
            new Command\BuildCommand($container)
        );

        $cli->deploy();
    }
}
