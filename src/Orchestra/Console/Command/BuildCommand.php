<?php

namespace Orchestra\Console\Command;

use GSpataro\CLI\Helper\Stopwatch;
use Orchestra\Pages\Pages;
use Orchestra\Project\Prototype;
use Orchestra\Finder\Researcher;
use Orchestra\Assets\Media;
use Orchestra\Library\ReadersCollection;
use Orchestra\Pages\GeneratorsCollection;
use Orchestra\Contractor\BuildersCollection;
use Orchestra\Project\Sitemap;
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

    private readonly Pages $pages;
    private readonly Media $media;
    private readonly Sitemap $sitemap;
    private readonly Stopwatch $stopwatch;
    private readonly Prototype $prototype;
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

        $this->prototype = $this->app->get('project.prototype');
        $this->generators = $this->app->get('pages.generators');
        $this->readers = $this->app->get('library.readers');
        $this->builders = $this->app->get('contractor.builders');
        $this->pages = $this->app->get('pages.collection');
        $this->media = $this->app->get('assets.media');
        $this->stopwatch = $this->app->get('cli.stopwatch');
        $this->researcher = $this->app->get('finder.researcher');
        $this->sitemap = $this->app->get('project.sitemap');

        $this->stopwatch->start();

        $this->runProcess(
            new RuntimeContents(
                $this->prototype,
                $this->readers
            )
        );

        $this->runProcess(
            new RuntimeSchemas(
                $this->prototype,
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
                    $this->media
                )
            );
        }


        $this->runProcess(
            new RuntimeSitemap(
                $this->sitemap
            )
        );

        $this->runProcess(
            new RuntimeCleanup(
                $this->sitemap
            )
        );

        $this->output->print('{bold}{fg_green}Build completed in ' . $this->stopwatch->stop() . ' seconds!');
    }
}
