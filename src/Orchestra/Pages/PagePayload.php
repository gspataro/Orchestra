<?php

namespace Orchestra\Pages;

use Orchestra\Project\Schema\ResolvedSchema;

final readonly class PagePayload
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
