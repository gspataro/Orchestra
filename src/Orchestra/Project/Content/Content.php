<?php

namespace Orchestra\Project\Content;

final readonly class Content
{
    public function __construct(
        public string $group,
        public string $reader,
        public string $path
    ) {
    }
}
