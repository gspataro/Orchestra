<?php

namespace Orchestra\Console\Command;

use GSpataro\CLI\Helper\Stopwatch;
use Orchestra\Pages\Pages;
use Orchestra\Project\Prototype;
use Orchestra\Finder\Researcher;
use Orchestra\Assets\Media;
use Orchestra\Console\Runtime\RuntimeBlueprint;
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
use Orchestra\Pipeline\BuildContext;
use Orchestra\Project\Blueprint;

final class BuildCommand extends BaseCommand
{
    protected string $name = 'build';
    protected ?string $description = 'Run the build process';

    private readonly Pages $pages;
    private readonly Media $media;
    private readonly Sitemap $sitemap;
    private readonly Stopwatch $stopwatch;
    private readonly BuildContext $context;
    private readonly Blueprint $blueprint;
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

        $this->context = $this->app->get('pipeline.context');
        $this->blueprint = $this->app->get('project.blueprint');
        $this->sitemap = $this->app->get('project.sitemap');
        $this->stopwatch = $this->app->get('cli.stopwatch');

        $this->stopwatch->start();

        $blueprintResult = $this->runProcess(
            new RuntimeBlueprint(
                $this->context,
                $this->blueprint
            )
        );

        if (!$blueprintResult) {
            exit(0);
        }

        $this->prototype = $this->app->get('project.prototype');

        $this->context->setContext(
            $this->blueprint,
            $this->prototype,
            $this->sitemap
        );

        $this->generators = $this->app->get('pages.generators');
        $this->readers = $this->app->get('library.readers');
        $this->builders = $this->app->get('contractor.builders');
        $this->pages = $this->app->get('pages.collection');
        $this->media = $this->app->get('assets.media');
        $this->researcher = $this->app->get('finder.researcher');

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
                    $this->context,
                    $this->media
                )
            );
        }


        $this->runProcess(
            new RuntimeSitemap(
                $this->context,
                $this->sitemap
            )
        );

        $this->runProcess(
            new RuntimeCleanup(
                $this->context,
                $this->sitemap
            )
        );

        $this->output->print('{bold}{fg_green}Build completed in ' . $this->stopwatch->stop() . ' seconds!');
    }
}
