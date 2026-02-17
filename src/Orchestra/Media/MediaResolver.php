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

    private function variantRelativePath(Media $media, MediaVariant $variant): string
    {
        $dirname = pathinfo($media->relativePath, PATHINFO_DIRNAME);
        $filename = pathinfo($media->relativePath, PATHINFO_FILENAME);

        $extension = $variant->format ?? pathinfo($media->relativePath, PATHINFO_EXTENSION);

        return pathJoin($dirname, $filename . '-' . $variant->name . '.' . $extension);
    }

    public function request(string $relativePath, ?string $variant = null): ?Media
    {
        if (!$this->repository->has($relativePath)) {
            $file = $this->context->paths->media($relativePath);

            if (!is_file($file)) {
                return null;
            }

            $publicPath = $this->context->paths->output(pathJoin('media', $relativePath));
            $mimeType = mime_content_type($file);

            $this->repository->add(new Media(
                $file,
                $relativePath,
                $publicPath,
                $mimeType
            ));
        }

        $media = $this->repository->get($relativePath);

        if ($variant) {
            $mediaVariant = $this->context->prototype->getMediaVariants()->get(strtok($media->mimeType, '/'), $variant);

            if ($mediaVariant) {
                $variantRelativePath = $this->variantRelativePath($media, $mediaVariant);

                $media->addTransformation($mediaVariant->toTransformation(
                    $mediaVariant->name,
                    $variantRelativePath,
                    $this->context->paths->output(pathJoin('media', $variantRelativePath))
                ));
            }
        }

        return $media;
    }

    public function resolve(string $relativePath, ?string $variant = null): ?string
    {
        $media = $this->request($relativePath, $variant);

        if (!$media) {
            return null;
        }

        if ($variant) {
            $mediaVariant = $media->getTransformation($variant);

            if (!$mediaVariant) {
                return null;
            }

            return $mediaVariant->relativePath;
        }

        return $media->relativePath;
    }

    /**
     * @param string $relativePath
     * @param string|null $variant
     * @return array{
     *      src: string,
     *      srcset: string[],
     *      sizes: string
     * }
     */
    public function resolveImage(string $relativePath, ?string $variant = null): array
    {
        $images = [
            'src' => null,
            'srcset' => [],
            'sizes' => null
        ];
        $media = $this->request($relativePath, $variant);

        if (!$media) {
            return $images;
        }

        $transformation = $variant ? $media->getTransformation($variant) : null;
        $srcRelativePath = $transformation ? $transformation->relativePath : $media->relativePath;

        /** @var \Orchestra\Project\MediaVariant\ImageMediaVariant */
        $imageVariant = $transformation ? $transformation->variant : null;

        $images['src'] = $srcRelativePath;
        $images['sizes'] = $imageVariant ? $imageVariant->width : null;

        $responsiveVariants = $this->context->blueprint->get('media.images.responsive');

        if (!$responsiveVariants || empty($responsiveVariants)) {
            return $images;
        }

        foreach ($responsiveVariants as $responsiveVariant) {
            $responsiveImage = $this->request($relativePath, $responsiveVariant);
            $responsiveTransformation = $responsiveImage->getTransformation($responsiveVariant);

            if ($responsiveTransformation) {
                /** @var \Orchestra\Project\MediaVariant\ImageMediaVariant */
                $originalVariant = $responsiveTransformation->variant;
                $images['srcset'][$originalVariant->width] = $responsiveTransformation->relativePath;
            }
        }

        return $images;
    }
}
