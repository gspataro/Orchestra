<?php

namespace Orchestra\Theme\Assets;

final class AssetRepository
{
    /** @var array<string,string[]> */
    private array $assets;

    public function add(string $type, string $entry): void
    {
        $this->assets[$type][] = $entry;
    }

    /**
     * @return string[]
     */
    public function css(): array
    {
        return $this->assets['css'] ?? [];
    }

    /**
     * @return string[]
     */
    public function js(): array
    {
        return $this->assets['js'] ?? [];
    }

    /**
     * @return array<string,string[]>
     */
    public function all(): array
    {
        return $this->assets;
    }
}
