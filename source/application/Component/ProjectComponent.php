<?php

namespace GSpataro\Application\Component;

use GSpataro\Project\Blueprint;
use GSpataro\DependencyInjection\Component;
use GSpataro\Project\Prototype;
use GSpataro\Project\Sitemap;

final class ProjectComponent extends Component
{
    public function register(): void
    {
        $this->container->variable('blueprintPath', DIR_ROOT . '/blueprint.json');

        $this->container->add('project.blueprint', function ($container, $args): object {
            return new Blueprint(
                $container->variable('blueprintPath')
            );
        });

        $this->container->add('project.prototype', function ($container, $args): object {
            return new Prototype(
                $container->get('project.blueprint')
            );
        });

        $this->container->add('project.sitemap', function ($container, $args): object {
            return new Sitemap();
        });
    }

    public function boot(): void
    {
    }
}
