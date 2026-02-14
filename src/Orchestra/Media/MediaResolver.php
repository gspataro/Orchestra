<?php

namespace Orchestra\Media;

use Orchestra\Pipeline\BuildContext;

final class MediaResolver
{
    public function __construct(
        private readonly BuildContext $context,
        private readonly MediaRepository $repository,
        private readonly VariantCollection $variants
    ) {
    }

    public function resolve(string $relativePath, ?string $variant = null): ?string
    {
        $file = $this->context->paths->media($relativePath);

        if (!is_file($file)) {
            return null;
        }

        $publicPath = $this->context->paths->output(pathJoin('media', $relativePath));
        $mimeType = mime_content_type($file);

        $media = new Media(
            $relativePath,
            $file,
            $publicPath,
            $mimeType
        );

        $variant = $this->variants->get(strtok($mimeType, '/'), $variant);
        $media->addVariant($variant);

        $this->repository->add($media);

        return $publicPath;
    }
}
