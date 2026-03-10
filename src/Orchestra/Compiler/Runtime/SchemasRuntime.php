<?php

namespace Orchestra\Compiler\Runtime;

use Orchestra\Content\ContentRepository;
use Orchestra\Page\GeneratorCollection;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Content\ContentCollection;
use Orchestra\Page\Factory\PageFactory;
use Orchestra\Page\Factory\SchemaFactory;
use Orchestra\Page\PageCollection;

final class SchemasRuntime extends Runtime
{
    private GeneratorCollection $generators;
    private ContentRepository $contents;
    private PageCollection $pages;
    private PageFactory $pageFactory;
    private SchemaFactory $schemaFactory;

    /**
     * Process schema contents
     *
     * @param \Orchestra\Project\Definition\Query\QueryDefinition[] $contentsReferences
     * @return ContentCollection[]
     */

    private function processSchemaContents(array $contentsReferences): array
    {
        $output = [];

        if (empty($contentsReferences)) {
            return $output;
        }

        foreach ($contentsReferences as $queryDefinition) {
            $contents = $this->contents->query($queryDefinition)->get();

            if (empty($queryDefinition->relationships)) {
                $output[$queryDefinition->group] = $contents;
                continue;
            }

            $contentsWithRelationships = [];

            foreach ($contents as $content) {
                $related = [];

                foreach ($queryDefinition->relationships as $relationship) {
                    $related[$relationship['with']] = $this->contents->group($relationship['with'])
                        ->query()
                        ->where(
                            $relationship['field'],
                            $relationship['operator'],
                            $content->get($relationship['value'])
                        )
                        ->get();
                }

                $contentsWithRelationships[$content->id] = $content->withRelationships($related);
            }

            $output[$queryDefinition->group] = !empty($contentsWithRelationships)
                ? new ContentCollection($contentsWithRelationships)
                : $contents;
        }

        return $output;
    }

    public function run(BuildOptions $options): bool
    {
        $this->generators = $this->container->get('pages.generators');
        $this->contents = $this->container->get('content.repository');
        $this->pages = $this->container->get('pages.collection');
        $this->pageFactory = $this->container->get('pages.factory');
        $this->schemaFactory = $this->container->get('pages.schema.factory');

        $this->output->info('Processing schemas');

        foreach ($this->context->prototype()->schemas() as $definition) {
            if ($this->context->options()->ignoreDrafts && $definition->draft) {
                $this->output->print("Skipping draft '{$definition->tag}'");
                continue;
            }

            $this->output->print("Working on schema '{$definition->tag}'");

            $schema = $this->schemaFactory->fromDefinition(
                $definition,
                $this->processSchemaContents($definition->contentsReferences)
            );
            $generator = $this->generators->get($schema->generator);

            foreach ($generator->generate($schema) as $payload) {
                $this->pages->add($this->pageFactory->fromPayload($payload));
            }
        }

        return true;
    }
}
