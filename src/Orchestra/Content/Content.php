<?php

namespace Orchestra\Content;

use Orchestra\Project\Source\ResolvedSource;

final readonly class Content
{
    public function __construct(
        public string $id,
        public string $tag,
        public string $group,
        public string $path,
        public mixed $body,
        public array $metadata = []
    ) {
    }
}
