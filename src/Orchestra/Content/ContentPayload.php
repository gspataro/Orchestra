<?php

namespace Orchestra\Content;

use Orchestra\Project\Source\ResolvedSource;

final readonly class ContentPayload
{
    public function __construct(
        public mixed $body,
        public array $metadata,
        public ResolvedSource $source
    ) {
    }
}
