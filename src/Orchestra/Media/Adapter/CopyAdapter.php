<?php

namespace Orchestra\Media\Adapter;

use Orchestra\Media\Media;
use Orchestra\Media\AdapterInterface;
use Orchestra\Media\Variant\Variant;

final class ImageAdapter implements AdapterInterface
{
    public function process(Media $media, ?Variant $transformation = null): void
    {
        copy($media->path, $media->publicPath);
    }
}
