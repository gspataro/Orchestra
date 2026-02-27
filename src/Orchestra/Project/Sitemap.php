<?php

namespace Orchestra\Project;

final class Sitemap
{
    /** @var array<string,string> */
    private array $sitemap = [];

    public function has(string $tag): bool
    {
        return isset($this->sitemap[$tag]);
    }

    public function hasPath(string $path): bool
    {
        return in_array($path, array_values($this->sitemap));
    }

    public function add(string $tag, string $path): string
    {
        if ($this->hasPath($path)) {
            $path = $this->generateUniquePath($path);
        }

        $this->sitemap[$tag] = $path;
        return $path;
    }

    public function generateUniquePath(string $path): string
    {
        if (!$this->hasPath($path)) {
            return $path;
        }

        while ($this->hasPath($path)) {
            $path = addSuffixToFilename($path, '-copy');
        }

        return $path;
    }

    public function get(string $tag): ?string
    {
        return $this->sitemap[$tag] ?? null;
    }

    public function getAll(): array
    {
        return $this->sitemap;
    }
}
