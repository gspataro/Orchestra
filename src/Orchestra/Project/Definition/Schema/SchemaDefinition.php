<?php

namespace Orchestra\Project\Definition\Schema;

final readonly class SchemaDefinition
{
    /**
     * @param string $tag
     * @param \Orchestra\Project\Definition\Query\QueryDefinition[] $contentsReferences
     * @param string $template
     * @param string $generator
     * @param string $source
     * @param string $builder
     * @param string $slug
     * @param array<string|int,mixed> $options
     */
    public function __construct(
        public string $tag,
        public array $contentsReferences,
        public string $template,
        public string $generator,
        public string $source,
        public string $builder,
        public string $slug,
        public array $options
    ) {
    }
}
