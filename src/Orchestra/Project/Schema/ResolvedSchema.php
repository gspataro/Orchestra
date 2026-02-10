<?php

namespace Orchestra\Project\Schema;

final class ResolvedSchema
{
    public function __construct(
        public string $tag,
        public array $contents,
        public string $template,
        public string $generator,
        public string $source,
        public string $builder,
        public string $slug,
        public array $options
    ) {
    }
}
