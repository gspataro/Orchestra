<?php

namespace Orchestra\Compiler\Runtime;

use Orchestra\Compiler\BuildOptions;
use Orchestra\Blueprint\Blueprint;
use Orchestra\Blueprint\BlueprintCompiler;
use Orchestra\Compiler\UrlGenerator;
use Orchestra\Project\PrototypeCompiler;
use Orchestra\Project\Sitemap;

final class CreateContextRuntime extends Runtime
{
    private Blueprint $blueprint;
    private BlueprintCompiler $blueprintCompiler;
    private PrototypeCompiler $prototypeCompiler;
    private Sitemap $sitemap;
    private BuildOptions $buildOptions;
    private UrlGenerator $urlGenerator;

    public function loadBlueprint(): bool
    {
        $this->blueprint = $this->container->get('blueprint');
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
        $namespaces = $this->blueprintCompiler->compile($this->blueprint);
        $prototype = $this->prototypeCompiler->compile($namespaces);

        $this->context->setContext(
            $prototype,
            $this->sitemap,
            $this->buildOptions
        );

        $this->urlGenerator->load();

        return true;
    }

    public function run(BuildOptions $options): bool
    {
        $this->buildOptions = $this->container->get('compiler.options');
        $this->blueprint = $this->container->get('blueprint');
        $this->blueprintCompiler = $this->container->get('blueprint.compiler');
        $this->prototypeCompiler = $this->container->get('project.prototype.compiler');
        $this->sitemap = $this->container->get('project.sitemap');
        $this->urlGenerator = $this->container->get('compiler.url');

        return $this->loadBlueprint() && $this->createContext();
    }
}
