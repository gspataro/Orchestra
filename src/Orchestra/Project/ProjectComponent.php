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
        $container->variable('blueprintPath', DIR_ROOT . '/blueprint.json');

        $container->add('project.blueprint', function ($container, $args): object {
            return new Blueprint(
                $container->variable('blueprintPath')
            );
        });

        $container->add('project.prototype', function ($container, $args): object {
            return new Prototype(
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
