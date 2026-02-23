<?php

namespace Orchestra\Project;

use GSpataro\DependencyInjection\Container;
use Orchestra\Project\Blueprint;
use Orchestra\Project\Sitemap;
use Orchestra\Application\Component;
use Orchestra\Project\Definition\WebsiteDefinition;

final class ProjectComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('project.configDefinitions', function ($c, $a): object {
            return new DefinitionCollection();
        });

        $container->add('project.blueprint', function ($container, $args): object {
            return new Blueprint();
        });

        $container->add('project.compiler', function ($container, $args): object {
            return new BlueprintCompiler(
                $container->get('project.blueprint'),
                $container->get('project.configDefinitions')
            );
        });

        $container->add('project.sitemap', function ($container, $args): object {
            return new Sitemap();
        });
    }

    public function boot(Container $container): void
    {
        /** @var ConfigDefinitionCollection */
        $configDefinitions = $container->get('project.configDefinitions');

        $configDefinitions->add(new WebsiteDefinition());
    }
}
