<?php

namespace Orchestra\Media;

use Orchestra\Media\Variant\Variant;

final class Media
{
    /** @var Variant[] */
    private array $variants = [];

    public function __construct(
        public readonly string $relativePath,
        public readonly string $path,
        public readonly string $publicPath,
        public readonly string|false $mimeType
    ) {
    }

    public function addVariant(Variant $variant): void
    {
        if (in_array($variant, $this->variants)) {
            return;
        }

        $this->variants[] = $variant;
    }

    /**
     * @return Variant[]
     */
    public function getVariants(): array
    {
        return $this->variants;
    }

    public function hasVariants(): bool
    {
        return empty($this->variants);
    }
}
