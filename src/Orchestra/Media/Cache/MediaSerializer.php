<?php

namespace Orchestra\Media\Cache;

use Orchestra\Media\Media;

final class MediaSerializer
{
    public function serialize(Media $media): string
    {
        return serialize(array_keys($media->getTransformations()));
    }

    public function unserialize(string $media): array
    {
        return unserialize($media);
    }
}
