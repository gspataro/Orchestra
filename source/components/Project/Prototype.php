<?php

namespace GSpataro\Project;

use GSpataro\Project\Exception\InvalidBlueprintException;
use GSpataro\Utilities\DotNavigator;

final class Prototype extends DotNavigator
{
    /**
     * Initialize prototype
     *
     * @param Blueprint $blueprint
     */

    public function __construct(
        private readonly Blueprint $blueprint
    ) {
        $this->readContents();
        $this->readSchemas();
    }

    /**
     * Read contents from blueprint
     *
     * @return void
     */

    public function readContents(): void
    {
        $this->set('contents', []);

        if (empty($this->blueprint->get('contents'))) {
            return;
        }

        foreach ($this->blueprint->get('contents') as $group => $source) {
            if (!str_contains($source, ':')) {
                throw new Exception\InvalidBlueprintException(
                    "Invalid data source for group '{$group}'. A data source must be in the format of 'reader:path'."
                );
            }

            [$reader, $path] = explode(':', $source, 2);

            $this->set('contents.' . $group, [
                'reader' => $reader,
                'path' => $path
            ]);
        }
    }

    /**
     * Read schemas from blueprint
     *
     * @return void
     */

    public function readSchemas(): void
    {
        $this->set('schemas', []);

        if (empty($this->blueprint->get('schemas'))) {
            return;
        }

        foreach ($this->blueprint->get('schemas') as $tag => $schema) {
            if (!isset($schema['template'])) {
                throw new Exception\InvalidBlueprintException(
                    "You must provide a template for schema '{$tag}'."
                );
            }

            if (!isset($schema['builder'])) {
                throw new Exception\InvalidBlueprintException(
                    "You must provide a builder for schema '{$tag}'."
                );
            }

            if (!isset($schema['generate'])) {
                throw new Exception\InvalidItemException(
                    "You must provide a generator for schema '{$tag}'."
                );
            }

            if (!isset($schema['slug'])) {
                throw new Exception\InvalidBlueprintException(
                    "You must provide a slug for schema '{$tag}'."
                );
            }

            if (!str_starts_with($schema['slug'], '/')) {
                $schema['slug'] = '/' . $schema['slug'];
            }

            if (str_contains($schema['generate'], ':')) {
                [$generator, $generateBasedOn] = explode(':', $schema['generate'], 2);
            }

            $contents = [];

            if (isset($schema['contents'])) {
                foreach ($schema['contents'] as $query) {
                    if (!is_array($query)) {
                        $contents[$query] = [
                            'group' => $query
                        ];
                        continue;
                    }

                    if (!isset($query['group'])) {
                        throw new InvalidBlueprintException(
                            "Invalid content query for schema '{$tag}'. A content group must be provided."
                        );
                    }

                    $queryLabel = $query['label'] ?? $query['group'];

                    $contents = array_merge($contents, [
                        $queryLabel => $query
                    ]);
                }
            }

            $schema['tag'] = $tag;
            $schema['contents'] = $contents;
            $schema['generator'] = $generator ?? $schema['generate'];
            $schema['generate_based_on'] = $generateBasedOn ?? '';
            $schema['options'] ??= [];

            $this->set('schemas.' . $tag, $schema);
        }
    }
}
