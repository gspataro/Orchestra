<?php

namespace Orchestra\Compiler\Runtime;

use Orchestra\Content\ContentRepository;
use Orchestra\Pages\GeneratorsCollection;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Content\ContentCollection;

final class SchemasRuntime extends Runtime
{
    private readonly GeneratorsCollection $generators;
    private readonly ContentRepository $contents;

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

        $this->output->info('Processing schemas');

        foreach ($this->context->prototype->schemas() as $schema) {
            $this->output->print("Working on schema '{$schema->tag}'");

            $resolvedSchema = $schema->withResolvedContents($this->processSchemaContents($schema->contentsReferences));

            $generator = $this->generators->get($schema->generator);
            $generator->generate($resolvedSchema);
        }

        return true;
    }
}
