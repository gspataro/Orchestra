<?php

namespace Orchestra\Media;

use Orchestra\Compiler\BuildContext;

final class MediaResolver
{
    public function __construct(
        private readonly BuildContext $context,
        private readonly MediaRepository $repository,
        private readonly MediaTransformer $transformer,
        private readonly PolicyCollection $policies
    ) {
    }

    public function request(string $relativePath, ?string $variant = null): ?Media
    {
        if (!$this->repository->has($relativePath)) {
            $file = $this->context->paths()->media($relativePath);

            if (!is_file($file)) {
                return null;
            }

            $publicPath = $this->context->paths()->output('media', $relativePath);
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
            $this->transformer->transform($media, $variant);
        }

        $policy = $this->policies->getFor($media->mimeType);

        if ($policy) {
            $policy->apply($media, $this->transformer, $this->context);
        }

        return $media;
    }

    /**
     * @param array<string,string[]> $requests
     * @return void
     */
    public function bulkRequest(array $requests): void
    {
        foreach ($requests as $relativePath => $variants) {
            if (empty($variants)) {
                $this->request($relativePath, null);
                continue;
            }

            foreach ($variants as $variant) {
                $this->request($relativePath, $variant);
            }
        }
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
}
