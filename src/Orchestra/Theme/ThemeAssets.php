<?php

namespace Orchestra\Theme;

final readonly class ThemeAssets
{
    public function __construct(
        public string $driver,
        public string $dir,
        public array $entries
    ) {
    }
}
