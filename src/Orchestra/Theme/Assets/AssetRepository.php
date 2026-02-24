<?php

namespace Orchestra\Theme\Assets;

final class AssetRepository
{
    /** @var array<string,string[]> */
    private array $assets;

    public function add(string $type, string $entry)
    {
        $this->assets[$type][] = $entry;
    }

    public function css(): array
    {
        return $this->assets['css'] ?? [];
    }

    public function js(): array
    {
        return $this->assets['js'] ?? [];
    }

    public function all(): array
    {
        return $this->assets;
    }
}
