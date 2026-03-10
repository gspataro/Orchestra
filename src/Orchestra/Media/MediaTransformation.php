<?php

namespace Orchestra\Media;

use Orchestra\Project\Definition\MediaVariant\MediaVariantDefinition;

final readonly class MediaTransformation
{
    public function __construct(
        public string $name,
        public string $relativePath,
        public string $publicPath,
        public MediaVariantDefinition $variant
    ) {
    }
}
