<?php

namespace Orchestra\Media;

final class MediaRepository
{
    /** @var Media[] */
    private array $items = [];

    public function add(Media $media): void
    {
        $this->items[$media->relativePath] = $media;
    }

    public function get(string $relativePath): ?Media
    {
        return $this->items[$relativePath] ?? null;
    }

    public function has(string $relativePath): bool
    {
        return isset($this->items[$relativePath]);
    }

    /**
     * @return Media[]
     */
    public function all(): array
    {
        return array_values($this->items);
    }
}
