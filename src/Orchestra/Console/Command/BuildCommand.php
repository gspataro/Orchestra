<?php

namespace Orchestra\Console\Command;

use GSpataro\CLI\Helper\Stopwatch;
use Orchestra\Pages\Pages;
use Orchestra\Finder\Researcher;
use Orchestra\Assets\Media;
use Orchestra\Console\Runtime\RuntimeCreateContext;
use Orchestra\Library\ReadersCollection;
use Orchestra\Pages\GeneratorsCollection;
use Orchestra\Contractor\BuildersCollection;
use Orchestra\Console\Runtime\RuntimeCleanup;
use Orchestra\Console\Runtime\RuntimeContents;
use Orchestra\Console\Runtime\RuntimeMedia;
use Orchestra\Console\Runtime\RuntimePages;
use Orchestra\Console\Runtime\RuntimeSchemas;
use Orchestra\Console\Runtime\RuntimeSitemap;
use Orchestra\Pipeline\BuildContext;

final class BuildCommand extends BaseCommand
{
    protected string $name = 'build';
    protected ?string $description = 'Run the build process';

    private readonly Pages $pages;
    private readonly Media $media;
    private readonly Stopwatch $stopwatch;
    private readonly BuildContext $context;
    private readonly Researcher $researcher;
    private readonly ReadersCollection $readers;
    private readonly BuildersCollection $builders;
    private readonly GeneratorsCollection $generators;

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

        $this->context = $this->app->get('pipeline.context');
        $this->stopwatch = $this->app->get('cli.stopwatch');

        $this->stopwatch->start();

        $blueprintResult = $this->runProcess(
            new RuntimeCreateContext(
                $this->context,
                $this->app
            )
        );

        if (!$blueprintResult) {
            exit(0);
        }

        $this->generators = $this->app->get('pages.generators');
        $this->readers = $this->app->get('library.readers');
        $this->builders = $this->app->get('contractor.builders');
        $this->pages = $this->app->get('pages.collection');
        $this->media = $this->app->get('assets.media');
        $this->researcher = $this->app->get('finder.researcher');

        $this->runProcess(
            new RuntimeContents(
                $this->context,
                $this->readers
            )
        );

        $this->runProcess(
            new RuntimeSchemas(
                $this->context,
                $this->generators,
                $this->researcher
            )
        );

        if ($this->argument('cleanup-only') !== false) {
            $this->runProcess(
                new RuntimePages(
                    $this->pages,
                    $this->builders
                )
            );
        }

        if ($this->argument('view-only') !== false && $this->argument('cleanup-only') !== false) {
            $this->runProcess(
                new RuntimeMedia(
                    $this->context,
                    $this->media
                )
            );
        }

        $this->runProcess(
            new RuntimeSitemap(
                $this->context
            )
        );

        $this->runProcess(
            new RuntimeCleanup(
                $this->context
            )
        );

        $this->output->print('{bold}{fg_green}Build completed in ' . $this->stopwatch->stop() . ' seconds!');
    }
}
