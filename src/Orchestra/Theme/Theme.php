<?php

namespace Orchestra\Theme;

final readonly class Theme
{
    public function __construct(
        public string $name,
        public string $path,
        public ThemeAssets $assets
    ) {
    }

    public function elements(): string
    {
        return pathJoin($this->path, 'elements');
    }
}
