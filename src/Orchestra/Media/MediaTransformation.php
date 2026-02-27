<?php

namespace Orchestra\Media;

use Orchestra\Project\Definition\MediaVariant\MediaVariantDefinition;

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
