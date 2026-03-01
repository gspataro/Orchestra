<?php

namespace Orchestra\Cache;

final class CacheSession
{
    /** @var string[] */
    private array $touchedKeys = [];

    public function add(string $path): void
    {
        $this->touchedKeys[] = $path;
    }

    /**
     * @return string[]
     */
    public function all(): array
    {
        return $this->touchedKeys;
    }
}
