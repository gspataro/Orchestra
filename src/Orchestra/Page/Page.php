<?php

namespace Orchestra\Page;

use Orchestra\Content\ContentCollection;
use Orchestra\Project\Schema\ResolvedSchema;

final readonly class Page
{
    /**
     * @param string $tag
     * @param string $permalink
     * @param array $contents
     * @param ResolvedSchema $schema
     */
    public function __construct(
        public string $tag,
        public string $permalink,
        public array $contents,
        public ResolvedSchema $schema
    ) {
    }
}
