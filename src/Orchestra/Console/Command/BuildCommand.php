<?php

namespace Orchestra\Console\Command;

use GSpataro\CLI\Helper\Stopwatch;
use Orchestra\Console\ConsoleOutputAdapter;
use Orchestra\Pipeline\Pipeline;

final class BuildCommand extends BaseCommand
{
    protected string $name = 'build';
    protected ?string $description = 'Run the build process';

    private readonly Stopwatch $stopwatch;

    public function options(): array
    {
        $options = [];

        $options['skip-media'] = [
            'type' => 'toggle'
        ];

        $options['cleanup-only'] = [
            'type' => 'toggle'
        ];

        return $options;
    }

    public function main(): void
    {
        $this->output->print('{bold}Running the building process{nl}');

        /** @var Stopwatch */
        $this->stopwatch = $this->container->get('console.stopwatch');
        $this->stopwatch->start();

        /** @var Pipeline */
        $pipeline = $this->container->get('pipeline', [
            'output.adapter' => new ConsoleOutputAdapter($this->output)
        ]);

        /** @var \Orchestra\Pipeline\BuildOptions */
        $buildOptions = $this->container->get('pipeline.options', [
            'skipMedia' => $this->argument('skip-media') !== null,
            'cleanupOnly' => $this->argument('cleanup-only') !== null
        ]);

        $result = $pipeline->run($buildOptions);

        if (!$result) {
            exit(0);
        }

        $this->output->print('{bold}{fg_green}Build completed in ' . $this->stopwatch->stop() . ' seconds!');
    }
}
