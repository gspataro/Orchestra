<?php

namespace Orchestra\Media\Variant;

final readonly class ImageVariant extends Variant
{
    public function __construct(
        public int $width,
        public int $height,
        public string $format,
        public int $quality
    ) {
    }
}
