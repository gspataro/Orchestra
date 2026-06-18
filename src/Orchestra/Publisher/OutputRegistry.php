<?php

namespace Orchestra\Publisher;

final class OutputRegistry
{
    /** @var string[] */
    private array $paths = [];

    public function add(string $path): void
    {
        $this->paths[] = $path;
    }

    /**
     * @return string[]
     */
    public function all(): array
    {
        return $this->paths;
    }
}
