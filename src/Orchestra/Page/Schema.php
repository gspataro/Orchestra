<?php

namespace Orchestra\Page;

use Orchestra\Content\ContentCollection;

final readonly class Schema
{
    /**
     * @param string $tag
     * @param ContentCollection[] $contents
     * @param string|null $template
     * @param string $generator
     * @param string $source
     * @param string $slug
     * @param array<string,mixed> $options
     */
    public function __construct(
        public string $tag,
        public array $contents,
        public ?string $template,
        public string $generator,
        public string $source,
        public string $slug,
        public array $options
    ) {
    }
}
