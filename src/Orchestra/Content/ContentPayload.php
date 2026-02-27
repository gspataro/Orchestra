<?php

namespace Orchestra\Content;

use Orchestra\Project\Definition\Source\Source;

final readonly class ContentPayload
{
    public function __construct(
        public mixed $body,
        public array $metadata,
        public Source $source
    ) {
    }
}
