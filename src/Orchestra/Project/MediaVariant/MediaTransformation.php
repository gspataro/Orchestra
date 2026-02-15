<?php

namespace Orchestra\Project\MediaVariant;

readonly class MediaTransformation
{
    public function __construct(
        public string $name,
        public string $publicPath,
        public MediaVariant $variant
    ) {
    }
}
