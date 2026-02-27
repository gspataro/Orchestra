<?php

namespace Orchestra\Project\Definition\MediaVariant;

readonly class MediaTransformation
{
    public function __construct(
        public string $name,
        public string $relativePath,
        public string $publicPath,
        public MediaVariantDefinition $variant
    ) {
    }
}
