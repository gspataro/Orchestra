<?php

namespace GSpataro\Application\Process;

use GSpataro\Finder\Researcher;
use GSpataro\Pages\GeneratorsCollection;
use GSpataro\Project\Prototype;

class ProcessSchemas extends Process
{
    public function __construct(
        private Prototype $prototype,
        private GeneratorsCollection $generators,
        private Researcher $researcher
    ) {
    }

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
        $this->output->print('{bold}Processing schemas');

        foreach ($this->prototype->get('schemas') as $tag => $schema) {
            $this->output->print("Working on schema '{$tag}'");

            $schema['contents'] = $this->processSchemaContents($schema['contents']);

            $generator = $this->generators->get($schema['generator']);
            $generator->generate($schema);
        }

        return true;
    }
}
