<?php

namespace Orchestra\Project\Definition\MediaVariant;

use Orchestra\Project\Definition\MediaVariant\MediaVariant;

final class MediaVariantCollection
{
    /** @var MediaVariant[] */
    private array $image = [];

    /** @var MediaVariant[] */
    private array $generic = [];

    public function __construct(array $variants)
    {
        $this->image = $variants['image'] ?? [];
        $this->generic = $variants['generic'] ?? [];
    }

    public function add(string $type, string $tag, MediaVariant $variant): void
    {
        if ($type === 'image') {
            $this->image[$tag] = $variant;
            return;
        }

        $this->generic[$tag] = $variant;
    }

    public function get(string $type, string $tag): ?MediaVariant
    {
        if ($type === 'image') {
            return $this->image[$tag] ?? null;
        }

        return $this->generic[$tag] ?? null;
    }

    public function image(string $tag): ?MediaVariant
    {
        return $this->get('image', $tag);
    }
}
