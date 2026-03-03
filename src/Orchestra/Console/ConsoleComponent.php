<?php

namespace Orchestra\Console;

use GSpataro\CLI\Handler;
use GSpataro\CLI\Helper\Stopwatch;
use GSpataro\CLI\CommandsCollection;
use GSpataro\DependencyInjection\Container;
use Orchestra\Console\Command\BuildCommand;
use Orchestra\Application\Component;
use Orchestra\Console\Command\CacheCleanCommand;
use Orchestra\Console\Command\RehearsalCommand;

final class ConsoleComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('console.commands', fn(): object => new CommandsCollection());

        $container->add('console', function ($container, $args): object {
            return new Handler(
                $container->get('console.commands')
            );
        });

        $container->add('console.stopwatch', function ($container, $args): object {
            return new Stopwatch();
        });
    }

    public function boot(Container $container): void
    {
        $cli = $container->get('console');
        $commands = $container->get('console.commands');

        $commands->register(
            new BuildCommand($container)
        );

        $commands->register(
            new RehearsalCommand($container)
        );

        $commands->register(
            new CacheCleanCommand($container)
        );

        $cli->deploy();
    }
}
