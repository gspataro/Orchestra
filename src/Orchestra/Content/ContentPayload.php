<?php

namespace Orchestra\Content;

final class ContentPayload
{
    public function __construct(
        public readonly string $id,
        public readonly string $tag,
        public readonly string $group,
        public readonly string $path,
        public readonly mixed $body,
        public readonly array $metadata = []
    ) {
    }
}
