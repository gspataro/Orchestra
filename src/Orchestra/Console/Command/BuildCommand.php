<?php

namespace Orchestra\Console\Command;

use GSpataro\CLI\Helper\Stopwatch;
use Orchestra\Console\Runtime\CreateContextRuntime;
use Orchestra\Console\Runtime\CleanupRuntime;
use Orchestra\Console\Runtime\ContentsRuntime;
use Orchestra\Console\Runtime\MediaRuntime;
use Orchestra\Console\Runtime\PagesRuntime;
use Orchestra\Console\Runtime\SchemasRuntime;
use Orchestra\Console\Runtime\SitemapRuntime;

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

        $this->stopwatch = $this->container->get('console.stopwatch');

        $this->stopwatch->start();

        if (!$this->runProcess(CreateContextRuntime::class)) {
            exit(0);
        }

        $this->runProcess(ContentsRuntime::class);
        $this->runProcess(SchemasRuntime::class);

        if ($this->argument('cleanup-only') !== false) {
            $this->runProcess(PagesRuntime::class);
        }

        if ($this->argument('view-only') !== false && $this->argument('cleanup-only') !== false) {
            $this->runProcess(MediaRuntime::class);
        }

        $this->runProcess(SitemapRuntime::class);
        $this->runProcess(CleanupRuntime::class);

        $this->output->print('{bold}{fg_green}Build completed in ' . $this->stopwatch->stop() . ' seconds!');
    }
}
