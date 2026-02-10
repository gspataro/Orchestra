<?php

namespace Orchestra\Pipeline\Runtime;

use Orchestra\Finder\Researcher;
use Orchestra\Pages\GeneratorsCollection;

final class SchemasRuntime extends Runtime
{
    private readonly GeneratorsCollection $generators;
    private readonly Researcher $researcher;

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

        foreach ($contents as $label => $query) {
            $research = $this->researcher->start($label, $query['group']);

            if (isset($query['select'])) {
                $research->select($query['select']);
            }

            if (!empty($query['where'])) {
                $field = array_key_first($query['where']);
                $value = $query['where'][$field];
                $research->where($field, $value);
            }

            if (isset($query['skip'])) {
                $research->select($query['skip']);
            }

            if (isset($query['limit'])) {
                $research->limit($query['limit']);
            }

            if (isset($query['orderBy'])) {
                $research->orderBy(
                    $query['orderBy'],
                    $query['order'] ?? 'asc'
                );
            }

            $output[$label] = $research->fetch();
        }

        return $output;
    }

    public function run(array $options = []): bool
    {
        $this->generators = $this->container->get('pages.generators');
        $this->researcher = $this->container->get('finder.researcher');

        $this->output->info('Processing schemas');

        foreach ($this->context->prototype->get('schemas') as $schema) {
            $this->output->print("Working on schema '{$schema->tag}'");

            $resolvedSchema = $schema->withResolvedContents($this->processSchemaContents($schema->contentsReferences));

            $generator = $this->generators->get($schema->generator);
            $generator->generate($resolvedSchema);
        }

        return true;
    }
}
