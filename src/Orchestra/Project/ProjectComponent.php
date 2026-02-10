<?php

namespace Orchestra\Project;

use GSpataro\DependencyInjection\Container;
use Orchestra\Project\Blueprint;
use Orchestra\Project\Prototype;
use Orchestra\Project\Sitemap;
use Orchestra\Application\Component;

final class ProjectComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('project.blueprint', function ($container, $args): object {
            return new Blueprint();
        });

        $container->add('project.compiler', function ($container, $args): object {
            return new BlueprintCompiler(
                $container->get('project.blueprint')
            );
        });

        $container->add('project.sitemap', function ($container, $args): object {
            return new Sitemap();
        });
    }

    public function boot(Container $container): void
    {
    }
}
