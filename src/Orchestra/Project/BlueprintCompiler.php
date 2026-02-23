<?php

namespace Orchestra\Project;

use Orchestra\Content\ContentQueryDefinition;
use Orchestra\Project\Source\Source;
use Orchestra\Project\Source\SourceCollection;
use Orchestra\Project\Exception\InvalidBlueprintException;
use Orchestra\Project\Exception\InvalidSchemaException;
use Orchestra\Project\MediaVariant\ImageMediaVariant;
use Orchestra\Project\MediaVariant\MediaVariant;
use Orchestra\Project\MediaVariant\MediaVariantCollection;
use Orchestra\Project\Schema\Schema;
use Orchestra\Project\Schema\SchemaCollection;

final class BlueprintCompiler
{
    /** @var Content[] */
    private array $contents = [];

    /** @var Schema[] */
    private array $schemas = [];

    /** @var MediaVariant[] */
    private array $mediaVariants = [];

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

    private function readMediaImages(array $images): void
    {
        if (empty($images) || empty($images['sizes'])) {
            return;
        }

        $format = $images['optimize']['strategy'] ?? null;

        foreach ($images['sizes'] as $size => $options) {
            $this->mediaVariants['image'][$size] = new MediaVariant(
                $size,
                $format ?? null,
                [
                    'width' => $options['width'] ?? null,
                    'height' => $options['height'] ?? null,
                    'quality' => $options['quality'] ?? null,
                    'crop' => $options['crop'] ?? null
                ]
            );
        }
    }

    private function readMediaOptions(): void
    {
        $this->readMediaImages($this->blueprint->get('media.images') ?? [
            'optimize' => 'webp',
            'sizes' => [
                'thumbnail' => ['width' => 200, 'height' => 200, 'resize' => true],
                'medium' => ['width' => 400, 'height' => 400],
                'large' => ['width' => 1024, 'height' => 1024],
                'original' => []
            ]
        ]);
    }

    public function compile(): Prototype
    {
        $this->readContents();
        $this->readSchemas();
        $this->readMediaOptions();

        $contentCollection = new SourceCollection($this->contents);
        $schemaCollection = new SchemaCollection($this->schemas);
        $mediaVariants = new MediaVariantCollection($this->mediaVariants);

        return new Prototype(
            $contentCollection,
            $schemaCollection,
            $mediaVariants
        );
    }
}
