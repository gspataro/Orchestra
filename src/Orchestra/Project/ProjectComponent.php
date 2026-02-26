<?php

namespace Orchestra\Project;

use GSpataro\DependencyInjection\Container;
use Orchestra\Project\Blueprint;
use Orchestra\Project\Sitemap;
use Orchestra\Application\Component;
use Orchestra\Project\Factory\PrototypeFactory;

final class ProjectComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('project.blueprint', function ($c, $a): object {
            return new Blueprint();
        });

        $container->add('project.prototype.factory', function ($c, $a): object {
            return new PrototypeFactory();
        });

        $container->add('project.compiler', function ($c, $a): object {
            return new BlueprintCompiler(
                $c->get('project.blueprint'),
                $c->get('project.prototype.factory')
            );
        });

        $container->add('project.sitemap', function ($c, $a): object {
            return new Sitemap();
        });
    }

    public function boot(Container $container): void
    {
    }
}
