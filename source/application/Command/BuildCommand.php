<?php

namespace GSpataro\Application\Command;

use GSpataro\Pages\Pages;
use GSpataro\Project\Prototype;
use GSpataro\Finder\Researcher;
use GSpataro\CLI\Helper\Stopwatch;
use GSpataro\Assets\Media;
use GSpataro\Library\ReadersCollection;
use GSpataro\Pages\GeneratorsCollection;
use GSpataro\Contractor\BuildersCollection;
use GSpataro\Project\Sitemap;
use GSpataro\Application\Process\ProcessCleanup;
use GSpataro\Application\Process\ProcessContents;
use GSpataro\Application\Process\ProcessMedia;
use GSpataro\Application\Process\ProcessPages;
use GSpataro\Application\Process\ProcessSchemas;
use GSpataro\Application\Process\ProcessSitemap;

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
            new ProcessContents(
                $this->prototype,
                $this->readers
            )
        );

        $this->runProcess(
            new ProcessSchemas(
                $this->prototype,
                $this->generators,
                $this->researcher
            )
        );

        if ($this->argument('cleanup-only') !== false) {
            $this->runProcess(
                new ProcessPages(
                    $this->pages,
                    $this->builders
                )
            );
        }

        if ($this->argument('view-only') !== false && $this->argument('cleanup-only') !== false) {
            $this->runProcess(
                new ProcessMedia(
                    $this->media
                )
            );
        }


        $this->runProcess(
            new ProcessSitemap(
                $this->sitemap
            )
        );

        $this->runProcess(
            new ProcessCleanup(
                $this->sitemap
            )
        );

        $this->output->print('{bold}{fg_green}Build completed in ' . $this->stopwatch->stop() . ' seconds!');
    }
}
