<?php

namespace Orchestra\Project\Definition\MediaVariant;

use Orchestra\Project\Definition\MediaVariant\MediaVariantDefinition;

final class MediaVariantDefinitionCollection
{
    /** @var array<string,MediaVariantDefinition[]> */
    private array $items = [];

    public function add(string $type, string $tag, MediaVariantDefinition $variant): void
    {
        $this->items[$type][$tag] = $variant;
    }

    public function get(string $type, string $tag): ?MediaVariantDefinition
    {
        return $this->items[$type][$tag] ?? null;
    }

    public function image(string $tag): ?MediaVariantDefinition
    {
        return $this->get('image', $tag);
    }
}
