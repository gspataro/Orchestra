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
}
