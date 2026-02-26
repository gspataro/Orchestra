<?php

namespace Orchestra\Media;

use Orchestra\Compiler\BuildContext;
use Orchestra\Project\Definition\MediaVariant\MediaVariant;

final class MediaTransformer
{
    public function __construct(
        private readonly BuildContext $context
    ) {
    }

    private function variantRelativePath(Media $media, MediaVariant $variant): string
    {
        $dirname = pathinfo($media->relativePath, PATHINFO_DIRNAME);
        $filename = pathinfo($media->relativePath, PATHINFO_FILENAME);

        $extension = $variant->format ?? pathinfo($media->relativePath, PATHINFO_EXTENSION);

        return pathJoin($dirname, $filename . '-' . $variant->name . '.' . $extension);
    }

    public function transform(Media $media, string $variant): void
    {
        $mediaVariant = $this->context->prototype->mediaVariants()->get(strtok($media->mimeType, '/'), $variant);

        if (!$mediaVariant) {
            return;
        }

        $variantRelativePath = $this->variantRelativePath($media, $mediaVariant);

        $media->addTransformation($mediaVariant->toTransformation(
            $mediaVariant->name,
            $variantRelativePath,
            $this->context->paths->output(pathJoin('media', $variantRelativePath))
        ));
    }
}
