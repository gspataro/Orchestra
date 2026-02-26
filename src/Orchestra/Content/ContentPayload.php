<?php

namespace Orchestra\Content;

final readonly class ContentPayload
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
