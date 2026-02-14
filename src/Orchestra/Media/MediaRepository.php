<?php

namespace Orchestra\Media;

final class MediaRepository
{
    /** @var Media[] */
    private array $items;

    public function add(Media $media): void
    {
        $this->items[] = $media;
    }

    /**
     * @return Media[]
     */
    public function all(): array
    {
        return $this->items;
    }
}
