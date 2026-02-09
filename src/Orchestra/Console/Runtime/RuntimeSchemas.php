<?php

namespace Orchestra\Console\Runtime;

use Orchestra\Finder\Researcher;
use Orchestra\Pages\GeneratorsCollection;

final class RuntimeSchemas extends Runtime
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

    protected function main(): mixed
    {
        $this->generators = $this->container->get('pages.generators');
        $this->researcher = $this->container->get('finder.researcher');

        $this->output->print('{bold}Processing schemas');

        foreach ($this->context->prototype->get('schemas') as $tag => $schema) {
            $this->output->print("Working on schema '{$tag}'");

            $schema['contents'] = $this->processSchemaContents($schema['contents']);

            $generator = $this->generators->get($schema['generator']);
            $generator->generate($schema);
        }

        return true;
    }
}
