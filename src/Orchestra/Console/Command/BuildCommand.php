<?php

namespace Orchestra\Console\Command;

use GSpataro\CLI\Helper\Stopwatch;
use Orchestra\Console\Runtime\RuntimeCreateContext;
use Orchestra\Console\Runtime\RuntimeCleanup;
use Orchestra\Console\Runtime\RuntimeContents;
use Orchestra\Console\Runtime\RuntimeMedia;
use Orchestra\Console\Runtime\RuntimePages;
use Orchestra\Console\Runtime\RuntimeSchemas;
use Orchestra\Console\Runtime\RuntimeSitemap;

final class BuildCommand extends BaseCommand
{
    protected string $name = 'build';
    protected ?string $description = 'Run the build process';

    private readonly Stopwatch $stopwatch;

    public function options(): array
    {
        $options = [];

        $options['view-only'] = [
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

        $this->stopwatch = $this->container->get('cli.stopwatch');

        $this->stopwatch->start();

        if (!$this->runProcess(RuntimeCreateContext::class)) {
            exit(0);
        }

        $this->runProcess(RuntimeContents::class);
        $this->runProcess(RuntimeSchemas::class);

        if ($this->argument('cleanup-only') !== false) {
            $this->runProcess(RuntimePages::class);
        }

        if ($this->argument('view-only') !== false && $this->argument('cleanup-only') !== false) {
            $this->runProcess(RuntimeMedia::class);
        }

        $this->runProcess(RuntimeSitemap::class);
        $this->runProcess(RuntimeCleanup::class);

        $this->output->print('{bold}{fg_green}Build completed in ' . $this->stopwatch->stop() . ' seconds!');
    }
}
