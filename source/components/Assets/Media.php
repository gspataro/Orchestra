<?php

namespace GSpataro\Assets;

use Imagick;

final class Media
{
    /**
     * Store sizes
     *
     * @var array
     */

    private array $sizes = [];

    /**
     * Add a new size
     *
     * @param string $tag
     * @param int|null $width
     * @param int|null $height
     * @param int $quality
     * @return void
     */

    public function addSize(string $tag, ?int $width, ?int $height, int $quality): void
    {
        $this->sizes[$tag] = [
            'width' => $width,
            'height' => $height,
            'quality' => $quality
        ];
    }

    /**
     * Resize a media in all available sizes
     *
     * @param string $filePath
     * @return void
     */

    public function resizeMedia(string $filePath): void
    {
        $baseOutputPath = pathJoin(DIR_OUTPUT, 'media');
        $fileName = pathinfo($filePath, PATHINFO_BASENAME);

        if (!is_dir($baseOutputPath)) {
            mkdir($baseOutputPath);
        }

        foreach ($this->sizes as $tag => $size) {
            $image = new Imagick($filePath);
            $imageProps = $image->getImageGeometry();

            if (!$image) {
                continue;
            }

            $outputPath = pathJoin($baseOutputPath, $tag);
            $outputFile = pathJoin($outputPath, $fileName);

            if (is_file($outputFile)) {
                continue;
            }

            if (!is_dir($outputPath)) {
                mkdir($outputPath);
            }

            if (in_array($image->getImageMimeType(), ['image/jpeg', 'image/jpg'])) {
                $image->setImageCompression(Imagick::COMPRESSION_JPEG);
            } else {
                $image->setImageCompression(Imagick::COMPRESSION_ZIP);
            }

            if ($image->getImageCompressionQuality() > $size['quality']) {
                $image->setImageCompressionQuality($size['quality']);
            }

            if (
                $imageProps['width'] === $size['width'] && $imageProps['height'] === $size['height']
                || $size['width'] === 0 && $size['height'] === 0
            ) {
                $image->writeImage($outputFile);
                continue;
            }

            $image->resizeImage($size['width'], $size['height'], Imagick::FILTER_UNDEFINED, 0.9);
            $image->writeImage($outputFile);
        }
    }
}
