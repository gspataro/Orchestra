<?php

namespace Orchestra\Project;

use GSpataro\DependencyInjection\Container;
use Orchestra\Project\Sitemap;
use Orchestra\Application\Component;
use Orchestra\Project\Factory\PrototypeFactory;

final class ProjectComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('project.prototype.factory', function ($c, $a): object {
            return new PrototypeFactory();
        });

        $container->add('project.prototype.compiler', function ($c, $a): object {
            return new PrototypeCompiler(
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
