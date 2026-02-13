<?php

namespace Orchestra\Pages\Page;

use Orchestra\Content\ContentCollection;
use Orchestra\Project\Schema\ResolvedSchema;

final readonly class Page
{
    /**
     * @param string $tag
     * @param string $permalink
     * @param ContentCollection|Content[] $contents
     * @param ResolvedSchema $schema
     */
    public function __construct(
        public string $tag,
        public string $permalink,
        public ContentCollection|array $contents,
        public ResolvedSchema $schema
    ) {
    }
}
