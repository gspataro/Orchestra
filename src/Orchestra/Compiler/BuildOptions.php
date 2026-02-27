<?php

namespace Orchestra\Compiler;

final readonly class BuildOptions
{
    public function __construct(
        public bool $skipMedia = false,
        public bool $cleanupOnly = false,
        public ?string $baseUrl = null
    ) {
    }
}
