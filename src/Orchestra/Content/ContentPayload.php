<?php

namespace Orchestra\Content;

use Orchestra\Content\Source;

final readonly class ContentPayload
{
    public function __construct(
        public mixed $body,
        public array $metadata,
        public Source $source
    ) {
    }
}
