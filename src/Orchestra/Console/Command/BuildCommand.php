<?php

namespace Orchestra\Console\Command;

use GSpataro\CLI\Helper\Stopwatch;
use Orchestra\Compiler\Pipeline\BuildPipeline;
use Orchestra\Console\ConsoleOutputAdapter;

final class BuildCommand extends BaseCommand
{
    protected string $name = 'build';
    protected ?string $description = 'Run the build process';

    private Stopwatch $stopwatch;

    /**
     * @return array<string,array<string,mixed>>
     */
    public function options(): array
    {
        $options = [];

        $options['skip-media'] = [
            'type' => 'toggle',
            'description' => 'Skip media generation'
        ];

        $options['cleanup-only'] = [
            'type' => 'toggle',
            'description' => 'Skip build and do only public dir cleanup'
        ];

        $options['ignore-drafts'] = [
            'type' => 'toggle',
            'description' => 'Ignore files marked as drafts'
        ];

        $options['theme-debug'] = [
            'type' => 'toggle',
            'description' => 'Enable theme debug mode'
        ];

        $options['base-url'] = [
            'type' => 'optional',
            'description' => 'Override website.url'
        ];

        return $options;
    }

    public function main(): void
    {
        $this->output->print('{bold}Running the building process{nl}');

        $this->stopwatch = $this->container->get('console.stopwatch');
        $this->stopwatch->start();

        /** @var \Orchestra\Compiler\Factory\PipelineFactory */
        $pipelineFactory = $this->container->get('compiler.pipeline.factory');

        /** @var \Orchestra\Compiler\Factory\BuildContextFactory */
        $contextFactory = $this->container->get('compiler.context.factory');

        $context = $contextFactory->make();
        $pipeline = $pipelineFactory->make(
            BuildPipeline::class,
            $context,
            new ConsoleOutputAdapter($this->output)
        );

        /** @var \Orchestra\Compiler\BuildOptions */
        $buildOptions = $this->container->get('compiler.options', [
            'skipMedia' => $this->argument('skip-media') !== null,
            'cleanupOnly' => $this->argument('cleanup-only') !== null,
            'ignoreDrafts' => $this->argument('ignore-drafts') === null,
            'themeDebug' => $this->argument('theme-debug') !== null,
            'baseUrl' => $this->argument('base-url')
        ]);

        if (!$pipeline->run($buildOptions)) {
            exit(1);
        }

        $this->output->print('{bold}{fg_green}Build completed in ' . $this->stopwatch->stop() . ' seconds!');
    }
}
