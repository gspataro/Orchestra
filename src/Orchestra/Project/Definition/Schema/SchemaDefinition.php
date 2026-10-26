<?php

namespace Orchestra\Project\Definition\Schema;

use Orchestra\Project\Definition\Query\QueryDefinition;

final readonly class SchemaDefinition
{
    /**
     * @param string $tag
     * @param QueryDefinition[] $contentsReferences
     * @param string|null $template
     * @param string $generator
     * @param string $source
     * @param string $slug
     * @param bool $draft
     * @param array<string|int,mixed> $options
     */
    public function __construct(
        public string $tag,
        public array $contentsReferences,
        public ?string $template,
        public string $generator,
        public string $source,
        public string $slug,
        public bool $draft,
        public array $options
    ) {
    }
}
