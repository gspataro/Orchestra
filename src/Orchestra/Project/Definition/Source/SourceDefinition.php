<?php

namespace Orchestra\Project\Definition\Source;

final readonly class SourceDefinition
{
    public function __construct(
        public string $group,
        public string $reader,
        public string $path
    ) {
    }

    public function withResolvedPaths(string $path, string $relativePath): Source
    {
        return new Source(
            $this->group,
            $this->reader,
            $path,
            $relativePath
        );
    }
}
