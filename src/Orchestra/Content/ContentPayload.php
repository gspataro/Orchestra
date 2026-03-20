<?php

namespace Orchestra\Content;

use Orchestra\Content\Source;

final readonly class ContentPayload
{
    /**
     * @param mixed $body
     * @param array<string|int,mixed> $metadata
     * @param Source $source
     */
    public function __construct(
        public mixed $body,
        public array $metadata,
        public Source $source
    ) {
    }
}
