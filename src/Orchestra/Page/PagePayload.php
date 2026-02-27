<?php

namespace Orchestra\Page;

use Orchestra\Project\Definition\Schema\Schema;

final readonly class PagePayload
{
    /**
     * @param string $tag
     * @param string $permalink
     * @param array $contents
     * @param Schema $schema
     */
    public function __construct(
        public string $tag,
        public string $permalink,
        public array $contents,
        public Schema $schema
    ) {
    }
}
