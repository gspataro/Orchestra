<?php

namespace Orchestra\Pipeline\Runtime;

use Orchestra\Content\ContentRepository;
use Orchestra\Pages\GeneratorsCollection;
use Orchestra\Pipeline\BuildOptions;

final class SchemasRuntime extends Runtime
{
    private readonly GeneratorsCollection $generators;
    private readonly ContentRepository $contents;

    /**
     * Process schema contents
     *
     * @param array $contents
     * @return array
     */

    private function processSchemaContents(array $contents): array
    {
        $output = [];

        if (empty($contents)) {
            return $output;
        }

        foreach ($contents as $label => $queryDefinition) {
            $output[$queryDefinition->group] = $this->contents->query($queryDefinition)->get();
            continue;

            if (!empty($query['where'])) {
                $field = $query['where']['field'];
                $value = $query['where']['value'];
                $contentQuery->where($field, $value);
            }

            if (isset($query['skip'])) {
                $contentQuery->skip($query['skip']);
            }

            if (isset($query['limit'])) {
                $contentQuery->limit($query['limit']);
            }

            if (isset($query['orderBy'])) {
                $contentQuery->orderBy(
                    $query['orderBy'],
                    $query['order'] ?? 'asc'
                );
            }

            $output[$label] = $contentQuery->get();
        }

        return $output;
    }

    public function run(BuildOptions $options): bool
    {
        $this->generators = $this->container->get('pages.generators');
        $this->contents = $this->container->get('content.repository');

        $this->output->info('Processing schemas');

        foreach ($this->context->prototype->getSchemas() as $schema) {
            $this->output->print("Working on schema '{$schema->tag}'");

            $resolvedSchema = $schema->withResolvedContents($this->processSchemaContents($schema->contentsReferences));

            $generator = $this->generators->get($schema->generator);
            $generator->generate($resolvedSchema);
        }

        return true;
    }
}
