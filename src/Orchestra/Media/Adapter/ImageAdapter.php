<?php

namespace Orchestra\Media\Adapter;

use Imagick;
use Intervention\Image\Format;
use Intervention\Image\ImageManager;
use Orchestra\Media\Media;
use Orchestra\Media\MediaTransformation;

final class ImageAdapter extends BaseAdapter
{
    protected array $supports = [
        'image/jpeg',
        'image/png'
    ];

    public function __construct(
        private readonly ImageManager $imageManager
    ) {
    }

    public function handler(Media $media, ?MediaTransformation $transformation = null): void
    {
        if (!$transformation) {
            copy($media->path, $media->publicPath);
            return;
        }

        /** @var \Orchestra\Project\Definition\MediaVariant\MediaVariantDefinition */
        $variant = $transformation->variant;

        $width = $variant->option('width');
        $height = $variant->option('height');
        $crop = $variant->option('crop');
        $format = $variant->format;
        $quality = $variant->option('quality');

        $image = $this->imageManager->decodePath($media->path);

        if ($crop && $width && $height) {
            $image->cover($width, $height);
        } else {
            $image->scale($width, $height);
        }

        $encoded = $image->encodeUsingFormat(Format::tryCreate($format), quality: $quality);

        $encoded->save($transformation->publicPath);
    }
}
