<?php

namespace Orchestra\Project\Source;

final readonly class Source
{
    public function __construct(
        public string $group,
        public string $reader,
        public string $path
    ) {
    }

    public function withResolvedPaths(string $path, string $relativePath): ResolvedSource
    {
        return new ResolvedSource(
            $this->group,
            $this->reader,
            $path,
            $relativePath
        );
    }
}
