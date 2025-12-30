<?php

namespace GSpataro\Project;

final class Sitemap
{
    /**
     * Store website sitemap
     *
     * @var array
     */

    private array $sitemap = [];

    /**
     * Verify if a page exists in the sitemap
     *
     * @param string $tag
     * @return bool
     */

    public function has(string $tag): bool
    {
        return isset($this->sitemap[$tag]);
    }

    /**
     * Verify if a path exists in the sitemap
     *
     * @param string $path
     * @return bool
     */

    public function hasPath(string $path): bool
    {
        return in_array($path, array_values($this->sitemap));
    }

    /**
     * Add a path to the sitemap and return its unique version
     *
     * @param string $tag
     * @param string $path
     * @return string
     */

    public function add(string $tag, string $path): string
    {
        if ($this->hasPath($path)) {
            $path = $this->generateUniquePath($path);
        }

        $this->sitemap[$tag] = $path;
        return $path;
    }

    /**
     * Generate a unique path
     *
     * @param string $path
     * @return string
     */

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

    /**
     * Get a path
     *
     * @param string $tag
     * @return string|null
     */

    public function get(string $tag): ?string
    {
        return $this->sitemap[$tag] ?? null;
    }

    /**
     * Get all the sitemap
     *
     * @return array
     */

    public function getAll(): array
    {
        return $this->sitemap;
    }
}
