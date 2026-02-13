<?php

namespace Orchestra\Project;

use Orchestra\Content\ContentQueryDefinition;
use Orchestra\Project\Source\Source;
use Orchestra\Project\Source\SourceCollection;
use Orchestra\Project\Exception\InvalidBlueprintException;
use Orchestra\Project\Exception\InvalidSchemaException;
use Orchestra\Project\Schema\Schema;
use Orchestra\Project\Schema\SchemaCollection;

final class BlueprintCompiler
{
    /** @var Content[] */
    private array $contents = [];

    /** @var Schema[] */
    private array $schemas = [];

    public function __construct(
        private readonly Blueprint $blueprint
    ) {
    }

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

            $this->contents[$group] = new Source($group, $reader, $path);
        }
    }

    private function validateSchema(string $tag, array $schema): void
    {
        if (!isset($schema['template'])) {
            throw new InvalidBlueprintException(
                "You must provide a template for schema '{$tag}'."
            );
        }

        if (!isset($schema['generate'])) {
            throw new InvalidSchemaException(
                "You must provide a generator for schema '{$tag}'."
            );
        }

        if (!isset($schema['slug'])) {
            throw new InvalidBlueprintException(
                "You must provide a slug for schema '{$tag}'."
            );
        }
    }

    private function sanitizeSchemaSlug(string $slug): string
    {
        if (!str_starts_with($slug, '/')) {
            $slug = '/' . $slug;
        }

        return $slug;
    }

    /**
     * @param array $contents
     * @return ContentQueryDefinition[]
     */
    private function buildContentQueries(array $contents = []): array
    {
        $contentQueries = [];

        foreach ($contents as $query) {
            if (!is_array($query)) {
                $contentQueries[] = new ContentQueryDefinition($query);
                continue;
            }

            $contentQueries[] = new ContentQueryDefinition(
                $query['group'],
                $query['where'] ?? [],
                $query['skip'] ?? 0,
                $query['limit'] ?? null,
                $query['order']['by'] ?? null,
                match ($query['order']['direction'] ?? 'asc') {
                    'asc' => SORT_ASC,
                    'desc' => SORT_DESC,
                    default => SORT_ASC
                }
            );
        }

        return $contentQueries;
    }

    private function readSchemas(): void
    {
        if (empty($this->blueprint->get('schemas'))) {
            return;
        }

        foreach ($this->blueprint->get('schemas') as $tag => $schema) {
            $this->validateSchema($tag, $schema);

            $this->schemas[] = new Schema(
                $tag,
                $this->buildContentQueries($schema['contents'] ?? []),
                $schema['template'],
                $schema['generate'],
                $schema['source'] ?? '',
                $schema['builder'] ?? 'twig',
                $this->sanitizeSchemaSlug($schema['slug']),
                $schema['options'] ?? []
            );
        }
    }

    public function compile(): Prototype
    {
        $this->readContents();
        $this->readSchemas();

        $contentCollection = new SourceCollection($this->contents);
        $schemaCollection = new SchemaCollection($this->schemas);

        return new Prototype(
            $contentCollection,
            $schemaCollection
        );
    }
}
