<?php

namespace Orchestra\Theme\Assets\Driver;

use Orchestra\Theme\Assets\AssetEntry;
use Orchestra\Theme\Assets\DriverInterface;
use Orchestra\Theme\Theme;

abstract class BaseDriver implements DriverInterface
{
    /** @var AssetEntry[] */
    protected array $entries = [];

    protected function addEntry(string $sourcePath, string $publicPath, bool $autoload = true): void
    {
        $extension = pathinfo($sourcePath, PATHINFO_EXTENSION);

        $type = match($extension) {
            'css' => 'css',
            'js'  => 'js',
            default => null
        };

        $this->entries[] = new AssetEntry($sourcePath, $publicPath, $type, $autoload);
    }

    abstract public function discover(Theme $theme): void;

    public function entries(): array
    {
        return $this->entries;
    }
}
