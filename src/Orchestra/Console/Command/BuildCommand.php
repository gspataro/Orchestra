<?php

namespace Orchestra\Console\Command;

use GSpataro\CLI\Helper\Stopwatch;
use Orchestra\Console\ConsoleOutputAdapter;

final class BuildCommand extends BaseCommand
{
    protected string $name = 'build';
    protected ?string $description = 'Run the build process';

    private Stopwatch $stopwatch;

    public function options(): array
    {
        $options = [];

        $options['skip-media'] = [
            'type' => 'toggle'
        ];

        $options['cleanup-only'] = [
            'type' => 'toggle'
        ];

        $options['base-url'] = [
            'type' => 'optional'
        ];

        return $options;
    }

    public function main(): void
    {
        $this->output->print('{bold}Running the building process{nl}');

        /** @var Stopwatch */
        $this->stopwatch = $this->container->get('console.stopwatch');
        $this->stopwatch->start();

        /** @var \Orchestra\Compiler\PipelineCollection */
        $pipeline = $this->container->get('compiler.pipeline');

        /** @var \Orchestra\Compiler\BuildOptions */
        $buildOptions = $this->container->get('compiler.options', [
            'skipMedia' => $this->argument('skip-media') !== null,
            'cleanupOnly' => $this->argument('cleanup-only') !== null,
            'baseUrl' => $this->argument('base-url')
        ]);

        if (
            !$pipeline->get('build', new ConsoleOutputAdapter($this->output))
                ->run($buildOptions)
        ) {
            exit(0);
        }

        $this->output->print('{bold}{fg_green}Build completed in ' . $this->stopwatch->stop() . ' seconds!');
    }
}
