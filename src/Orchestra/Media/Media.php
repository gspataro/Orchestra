<?php

namespace Orchestra\Media;

use Orchestra\Project\Definition\MediaVariant\MediaTransformation;

final class Media
{
    /** @var MediaTransformation[] */
    private array $transformations = [];

    public function __construct(
        public readonly string $path,
        public readonly string $relativePath,
        public readonly string $publicPath,
        public readonly string|false $mimeType
    ) {
    }

    public function addTransformation(MediaTransformation $transformation): void
    {
        if (in_array($transformation, $this->transformations)) {
            return;
        }

        $this->transformations[$transformation->name] = $transformation;
    }

    public function getTransformation(string $name): ?MediaTransformation
    {
        return $this->transformations[$name] ?? null;
    }

    /**
     * @return MediaTransformation[]
     */
    public function getTransformations(): array
    {
        return $this->transformations;
    }

    public function hasTransformations(): bool
    {
        return !empty($this->transformations);
    }
}
