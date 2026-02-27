<?php

namespace Orchestra\Project\Definition\Schema;

final readonly class SchemaDefinition
{
    public array $contents;

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

    public function withResolvedContents(array $contents)
    {
        return new Schema(
            $this->tag,
            $contents,
            $this->template,
            $this->generator,
            $this->source,
            $this->builder,
            $this->slug,
            $this->options
        );
    }
}
