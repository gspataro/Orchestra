<?php

namespace Orchestra\Media;

use Orchestra\Media\Variant\Variant;

final class VariantCollection
{
    /** @var \Orchestra\Media\Variant\ImageVariant[] */
    private array $image = [];

    /** @var Variant[] */
    private array $generic = [];

    public function add(string $type, string $tag, Variant $variant): void
    {
        if ($type === 'image') {
            $this->image[$tag] = $variant;
            return;
        }

        $this->generic[$tag] = $variant;
    }

    public function get(string $type, string $tag): ?Variant
    {
        if ($type === 'image') {
            return $this->image[$tag] ?? null;
        }

        return $this->generic[$tag] ?? null;
    }
}
