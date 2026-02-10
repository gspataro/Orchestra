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
}
