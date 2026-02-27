<?php

namespace Orchestra\Compiler\Runtime;

use Orchestra\Compiler\BuildOptions;
use Orchestra\Blueprint\Blueprint;
use Orchestra\Project\BlueprintCompiler;
use Orchestra\Project\Sitemap;

final class CreateContextRuntime extends Runtime
{
    private Blueprint $blueprint;
    private BlueprintCompiler $compiler;
    private Sitemap $sitemap;

    public function loadBlueprint(): bool
    {
        $this->blueprint = $this->container->get('project.blueprint');
        $blueprintFile = $this->context->paths->root('blueprint.json');

        if (!is_file($blueprintFile)) {
            $this->output->error('Blueprint file not found in project root.');
            return false;
        }

        $rawBlueprint = file_get_contents($blueprintFile);

        if (!json_validate($rawBlueprint)) {
            $this->output->error('Invalid blueprint. A valid blueprint must be a JSON object.');
            return false;
        }

        $data = json_decode($rawBlueprint, true);
        $this->blueprint->init($data);

        return true;
    }

    public function createContext(): bool
    {
        /** @var BlueprintCompiler */
        $this->compiler = $this->container->get('project.compiler');

        /** @var Sitemap */
        $this->sitemap = $this->container->get('project.sitemap');

        $this->context->setContext(
            $this->blueprint,
            $this->compiler->compile(),
            $this->sitemap
        );

        return true;
    }

    public function run(BuildOptions $options): bool
    {
        return $this->loadBlueprint() && $this->createContext();
    }
}
