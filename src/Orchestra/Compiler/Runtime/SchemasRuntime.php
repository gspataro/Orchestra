<?php

namespace Orchestra\Compiler\Runtime;

use Orchestra\Content\ContentRepository;
use Orchestra\Page\GeneratorCollection;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Content\ContentCollection;
use Orchestra\Page\Factory\PageFactory;
use Orchestra\Page\PageCollection;

final class SchemasRuntime extends Runtime
{
    private readonly GeneratorCollection $generators;
    private readonly ContentRepository $contents;
    private readonly PageCollection $pages;
    private readonly PageFactory $pageFactory;

    /**
     * Process schema contents
     *
     * @param array $contents
     * @return ContentCollection[]
     */

    private function processSchemaContents(array $contents): array
    {
        $output = [];

        if (empty($contents)) {
            return $output;
        }

        foreach ($contents as $queryDefinition) {
            $output[$queryDefinition->group] = $this->contents->query($queryDefinition)->get();
        }

        return $output;
    }

    public function run(BuildOptions $options): bool
    {
        $this->generators = $this->container->get('pages.generators');
        $this->contents = $this->container->get('content.repository');
        $this->pages = $this->container->get('pages.collection');
        $this->pageFactory = $this->container->get('pages.factory');

        $this->output->info('Processing schemas');

        foreach ($this->context->prototype->schemas() as $schema) {
            $this->output->print("Working on schema '{$schema->tag}'");

            $resolvedSchema = $schema->withResolvedContents($this->processSchemaContents($schema->contentsReferences));
            $generator = $this->generators->get($schema->generator);

            foreach ($generator->generate($resolvedSchema) as $payload) {
                $this->pages->add($this->pageFactory->fromPayload($payload));
            }
        }

        return true;
    }
}
