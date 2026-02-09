<?php

namespace Orchestra\Console\Runtime;

use GSpataro\DependencyInjection\Container;
use Orchestra\Pipeline\BuildContext;
use Orchestra\Project\Blueprint;
use Orchestra\Project\Prototype;
use Orchestra\Project\Sitemap;

class RuntimeCreateContext extends Runtime
{
    private Blueprint $blueprint;
    private Prototype $prototype;
    private Sitemap $sitemap;

    public function __construct(
        private readonly BuildContext $context,
        private readonly Container $container
    ) {
    }

    public function loadBlueprint(): bool
    {
        $this->blueprint = $this->container->get('project.blueprint');
        $blueprintFile = $this->context->paths->root('blueprint.json');

        if (!is_file($blueprintFile)) {
            $this->output->print('{bold}{fg_red}Blueprint file not found in project root.');
            return false;
        }

        $rawBlueprint = file_get_contents($blueprintFile);

        if (!json_validate($rawBlueprint)) {
            $this->output->print('{bold}{fg_red}Invalid blueprint. A valid blueprint must be a JSON object.');
            return false;
        }

        $data = json_decode($rawBlueprint, true);
        $this->blueprint->init($data);

        return true;
    }

    public function createContext(): bool
    {
        $this->prototype = $this->container->get('project.prototype');
        $this->sitemap = $this->container->get('project.sitemap');

        $this->context->setContext(
            $this->blueprint,
            $this->prototype,
            $this->sitemap
        );

        return true;
    }

    public function main(): bool
    {
        return $this->loadBlueprint() && $this->createContext();
    }
}
