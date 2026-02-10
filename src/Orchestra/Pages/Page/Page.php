<?php

namespace Orchestra\Pages\Page;

use Orchestra\Project\Schema\ResolvedSchema;

final readonly class Page
{
    public function __construct(
        public string $tag,
        public string $permalink,
        public array $contents,
        public ResolvedSchema $schema
    ) {
    }
}
