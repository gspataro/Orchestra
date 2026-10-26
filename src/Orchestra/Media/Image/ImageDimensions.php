<?php

namespace Orchestra\Media\Image;

final readonly class ImageDimensions
{
    public function __construct(
        public int $width,
        public int $height
    ) {
    }
}
