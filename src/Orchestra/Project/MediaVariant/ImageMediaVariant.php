<?php

namespace Orchestra\Project\MediaVariant;

final readonly class ImageMediaVariant extends MediaVariant
{
    public function __construct(
        public string $name,
        public int $width,
        public int $height,
        public string $format,
        public int $quality
    ) {
    }
}
