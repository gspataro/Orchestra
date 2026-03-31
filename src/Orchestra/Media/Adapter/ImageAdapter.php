<?php

namespace Orchestra\Media\Adapter;

use Imagick;
use Orchestra\Media\Media;
use Orchestra\Media\MediaTransformation;

final class ImageAdapter extends BaseAdapter
{
    protected array $supports = [
        'image/jpeg',
        'image/png'
    ];

    private function processImagick(Media $media, MediaTransformation $transformation): void
    {
        $image = new Imagick($media->path);
        $imageGeometry = $image->getImageGeometry();

        /** @var \Orchestra\Project\Definition\MediaVariant\MediaVariantDefinition */
        $variant = $transformation->variant;

        $width = $variant->option('width');
        $height = $variant->option('height');
        $crop = $variant->option('crop');
        $format = $variant->format;
        $quality = $variant->option('quality');

        if ($imageGeometry['width'] >= $width || $imageGeometry['height'] >= $height) {
            if ($width && $height) {
                if ($crop) {
                    $image->cropThumbnailImage($width, $height);
                } else {
                    $image->thumbnailImage($width, $height, true);
                }
            } elseif ($width || $height) {
                $image->resizeImage($width ?? 0, $height ?? 0, Imagick::FILTER_LANCZOS, 1);
            }
        }

        if ($format === 'webp') {
            $image->setImageFormat($format);
        }

        if ($quality) {
            $image->setImageCompressionQuality($quality);
        }

        $image->writeImage($transformation->publicPath);
        $image->clear();
    }

    private function processGd(Media $media, MediaTransformation $transformation): void
    {
        [$imageWidth, $imageHeight, $imageType] = getimagesize($media->path);

        $image = match ($imageType) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($media->path),
            IMAGETYPE_PNG => imagecreatefrompng($media->path),
            default => null
        };

        /** @var \Orchestra\Project\Definition\MediaVariant\MediaVariantDefinition */
        $variant = $transformation->variant;

        $width = $variant->option('width');
        $height = $variant->option('height');
        $crop = $variant->option('crop');
        $format = $variant->format ?? image_type_to_extension($imageType);
        $quality = $variant->option('quality') ?? 100;

        if (is_null($width) && is_null($height)) {
            $newWidth = $imageWidth;
            $newHeight = $imageHeight;
        } elseif ($width && is_null($height)) {
            $ratio = $width / $imageWidth;
            $newWidth = $width;
            $newHeight = (int) round($imageHeight * $ratio);
        } elseif (is_null($width) && $height) {
            $ratio = $height / $imageHeight;
            $newWidth = (int) round($imageWidth * $ratio);
            $newHeight = $height;
        } else {
            if ($crop) {
                $ratio = max($width / $imageWidth, $height / $imageHeight);
            } else {
                $ratio = min($width / $imageWidth, $height / $imageHeight);
            }

            $tempWidth = (int) round($imageWidth * $ratio);
            $tempHeight = (int) round($imageHeight * $ratio);

            if ($crop) {
                $x = (int) round(($tempWidth - $width) / 2);
                $y = (int) round(($tempHeight - $height) / 2);

                $tempImage = imagecreatetruecolor($tempHeight, $tempWidth);
                imagecopyresampled($tempImage, $image, 0, 0, 0, 0, $tempWidth, $tempHeight, $width, $height);

                $publicImage = imagecreatetruecolor($width, $height);
                imagecopy($publicImage, $tempImage, 0, 0, $x, $y, $width, $height);

                $newWidth = $width;
                $newHeight = $height;
            } else {
                $newWidth = $tempWidth;
                $newHeight = $tempHeight;
            }
        }

        if (!isset($publicImage)) {
            $publicImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($publicImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $imageWidth, $imageHeight);
        }

        match ($format) {
            'jpg', 'jpeg' => imagejpeg($publicImage, $transformation->publicPath, $quality),
            'png' => imagepng($publicImage, $transformation->publicPath),
            'webp' => imagewebp($publicImage, $transformation->publicPath, $quality),
            default => null
        };
    }

    public function handler(Media $media, ?MediaTransformation $transformation = null): void
    {
        if (!$transformation) {
            copy($media->path, $media->publicPath);
            return;
        }

        if (class_exists(Imagick::class)) {
            $this->processImagick($media, $transformation);
            return;
        }

        $this->processGd($media, $transformation);
    }
}
