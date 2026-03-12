<?php

namespace Orchestra\Compiler;

final readonly class BuildOptions
{
    public function __construct(
        public bool $skipMedia = false,
        public bool $cleanupOnly = false,
        public bool $ignoreDrafts = true,
        public bool $themeDebug = false,
        public ?string $baseUrl = null,
        public ?string $context = 'build'
    ) {
    }
}
