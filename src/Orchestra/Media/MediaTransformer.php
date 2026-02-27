<?php

namespace Orchestra\Media;

use Orchestra\Compiler\BuildContext;
use Orchestra\Media\Factory\MediaTransformationFactory;
use Orchestra\Project\Definition\MediaVariant\MediaVariantDefinition;

final class MediaTransformer
{
    public function __construct(
        private readonly BuildContext $context,
        private readonly MediaTransformationFactory $transformationFactory
    ) {
    }

    private function variantRelativePath(Media $media, MediaVariantDefinition $variant): string
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
        $transformation = $this->transformationFactory->fromDefinition(
            $mediaVariant,
            $this->context->paths->output(pathJoin('media', $variantRelativePath)),
            $variantRelativePath
        );

        $media->addTransformation($transformation);
    }
}
