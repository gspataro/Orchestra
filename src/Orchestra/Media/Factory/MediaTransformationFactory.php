<?php

namespace Orchestra\Media\Factory;

use Orchestra\Media\MediaTransformation;
use Orchestra\Project\Definition\MediaVariant\MediaVariantDefinition;

final class MediaTransformationFactory
{
    public function fromDefinition(
        MediaVariantDefinition $definition,
        string $path,
        string $relativePath
    ): MediaTransformation {
        return new MediaTransformation(
            $definition->name,
            $relativePath,
            $path,
            $definition
        );
    }
}
