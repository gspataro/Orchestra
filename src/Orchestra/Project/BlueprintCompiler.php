<?php

namespace Orchestra\Project;

use Orchestra\Project\Content\Content;
use Orchestra\Project\Content\ContentCollection;
use Orchestra\Project\Exception\InvalidBlueprintException;
use Orchestra\Project\Exception\InvalidItemException;
use Orchestra\Project\Schema\Schema;
use Orchestra\Project\Schema\SchemaCollection;

final class BlueprintCompiler
{
    /** @var Content[] */
    private array $contents = [];

    private array $schemas = [];

    public function __construct(
        private readonly Blueprint $blueprint
    ) {
    }

    /**
     * Read contents from blueprint
     *
     * @return void
     */

    private function readContents(): void
    {
        if (empty($this->blueprint->get('contents'))) {
            return;
        }

        foreach ($this->blueprint->get('contents') as $group => $source) {
            if (!str_contains($source, ':')) {
                throw new InvalidBlueprintException(
                    "Invalid data source for group '{$group}'. A data source must be in the format of 'reader:path'."
                );
            }

            [$reader, $path] = explode(':', $source, 2);

            $this->contents[$group] = new Content($group, $reader, $path);
        }
    }

    /**
     * Read schemas from blueprint
     *
     * @return void
     */

    private function readSchemas(): void
    {
        if (empty($this->blueprint->get('schemas'))) {
            return;
        }

        foreach ($this->blueprint->get('schemas') as $tag => $schema) {
            if (!isset($schema['template'])) {
                throw new InvalidBlueprintException(
                    "You must provide a template for schema '{$tag}'."
                );
            }

            if (!isset($schema['generate'])) {
                throw new InvalidItemException(
                    "You must provide a generator for schema '{$tag}'."
                );
            }

            if (!isset($schema['slug'])) {
                throw new InvalidBlueprintException(
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

            $this->schemas[$tag] = new Schema(
                $tag,
                $contents,
                $schema['template'],
                $generator ?? $schema['generate'],
                $schema['generate_based_on'],
                $schema['builder'] ?? 'twig',
                $schema['slug'],
                $schema['options'] ?? []
            );
        }
    }

    public function compile(): Prototype
    {
        $this->readContents();
        $this->readSchemas();

        $contentCollection = new ContentCollection($this->contents);
        $schemaCollection = new SchemaCollection($this->schemas);

        return new Prototype(
            $contentCollection,
            $schemaCollection
        );
    }
}
