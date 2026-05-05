<?php

namespace Orchestra\Theme\Assets;

final readonly class AssetEntry
{
    public function __construct(
        public string $sourcePath,
        public string $publicPath,
        public ?string $type,
        public bool $autoload = true
    ) {
    }
}
