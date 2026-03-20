<?php

namespace Orchestra\Media\Cache;

use Orchestra\Cache\SignatureGeneratorInterface;
use Orchestra\Media\Media;

final class MediaSignatureGenerator
{
    public function __construct(
        private readonly SignatureGeneratorInterface $signature
    ) {
    }

    public function generateFromMedia(Media $media): string
    {
        $filemtime = filemtime($media->path);
        $size = filesize($media->path);

        return $this->signature->generateFromSeeds(
            $media->relativePath,
            $filemtime,
            $size
        );
    }
}
