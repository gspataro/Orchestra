<?php

namespace Orchestra\Media\Image;

use Orchestra\Project\Definition\MediaVariant\MediaVariantDefinition;

final class ImageDescriptor
{
    private readonly int $sourceWidth;
    private readonly int $sourceHeight;

    public function __construct(
        public string $path,
    ) {
        [$this->sourceWidth, $this->sourceHeight] = getimagesize($path);
    }

    public function sourceWidth(): int
    {
        return $this->sourceWidth;
    }

    public function sourceHeight(): int
    {
        return $this->sourceHeight;
    }

    public function predict(MediaVariantDefinition $variant): ImageDimensions
    {
        $targetWidth = $variant->option('width');
        $targetHeight = $variant->option('height');
        $crop = $variant->option('crop') ?? false;

        return $this->calculateDimensions($targetWidth, $targetHeight, $crop);
    }

    public function calculateDimensions(
        ?int $targetWidth = null,
        ?int $targetHeight = null,
        bool $crop = false
    ): ImageDimensions
    {
        if (is_null($targetWidth) && is_null($targetHeight)) {
            return new ImageDimensions($this->sourceWidth, $this->sourceHeight);
        }

        if ($targetWidth && is_null($targetHeight)) {
            $ratio = $targetWidth / $this->sourceWidth;
            return new ImageDimensions(
                $targetWidth,
                (int) round($this->sourceHeight * $ratio)
            );
        }

        if ($targetHeight && is_null($targetWidth)) {
            $ratio = $targetHeight / $this->sourceHeight;
            return new ImageDimensions(
                (int) round($this->sourceWidth * $ratio),
                $targetHeight
            );
        }

        if ($crop) {
            return new ImageDimensions($targetWidth, $targetHeight);
        }

        $ratio = min($targetWidth / $this->sourceWidth, $targetHeight / $this->sourceHeight);
        return new ImageDimensions(
            (int) round($this->sourceWidth * $ratio),
            (int) round($this->sourceHeight * $ratio)
        );
    }
}
