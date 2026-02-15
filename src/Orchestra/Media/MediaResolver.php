<?php

namespace Orchestra\Media;

use Orchestra\Pipeline\BuildContext;
use Orchestra\Project\MediaVariant\MediaVariant;

final class MediaResolver
{
    public function __construct(
        private readonly BuildContext $context,
        private readonly MediaRepository $repository
    ) {
    }

    private function variantPublicPath(Media $media, MediaVariant $variant): string
    {
        $dirname = pathinfo($media->publicPath, PATHINFO_DIRNAME);
        $filename = pathinfo($media->publicPath, PATHINFO_FILENAME);

        $extension = $variant->format ?? pathinfo($media->publicPath, PATHINFO_EXTENSION);

        return pathJoin($dirname, $filename . '-' . $variant->name . '.' . $extension);
    }

    public function resolve(string $relativePath, ?string $variant = null): ?string
    {
        if (!$this->repository->has($relativePath)) {
            $file = $this->context->paths->media($relativePath);

            if (!is_file($file)) {
                return null;
            }

            $publicPath = $this->context->paths->output(pathJoin('media', $relativePath));
            $mimeType = mime_content_type($file);

            $this->repository->add(new Media(
                $relativePath,
                $file,
                $publicPath,
                $mimeType
            ));
        }

        $media = $this->repository->get($relativePath);

        if (!$media) {
            return null;
        }

        if ($variant) {
            $mediaVariant = $this->context->prototype->getMediaVariants()->get(strtok($media->mimeType, '/'), $variant);

            if (!$mediaVariant) {
                return null;
            }

            $media->addTransformation($mediaVariant->toTransformation(
                $mediaVariant->name,
                $this->variantPublicPath($media, $mediaVariant)
            ));
        }

        return $media->publicPath;
    }
}
