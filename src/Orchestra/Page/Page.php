<?php

namespace Orchestra\Page;

use Orchestra\Page\Schema;

final readonly class Page
{
    /**
     * @param string $tag
     * @param string $permalink
     * @param array<string|int,mixed> $contents
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
