<?php

namespace Orchestra\Project\Source;

final readonly class ResolvedSource
{
    public function __construct(
        public string $group,
        public string $reader,
        public string $path,
        public string $relativePath
    ) {
    }
}
