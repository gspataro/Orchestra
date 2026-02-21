<?php

namespace Orchestra\Pipeline;

final readonly class BuildOptions
{
    public function __construct(
        public bool $skipMedia = false,
        public bool $cleanupOnly = false
    ) {
    }
}
