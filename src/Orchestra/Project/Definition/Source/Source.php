<?php

namespace Orchestra\Project\Definition\Source;

final readonly class Source
{
    public function __construct(
        public string $group,
        public string $reader,
        public string $path,
        public string $relativePath
    ) {
    }
}
