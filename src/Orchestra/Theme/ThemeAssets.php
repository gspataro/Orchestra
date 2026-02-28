<?php

namespace Orchestra\Theme;

final readonly class ThemeAssets
{
    /**
     * @param string $driver
     * @param string $dir
     * @param string[] $entries
     */
    public function __construct(
        public string $driver,
        public string $dir,
        public array $entries
    ) {
    }
}
