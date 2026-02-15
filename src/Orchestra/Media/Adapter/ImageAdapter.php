<?php

namespace Orchestra\Media\Adapter;

use Orchestra\Media\Media;
use Orchestra\Media\AdapterInterface;
use Orchestra\Project\MediaVariant\MediaTransformation;

final class ImageAdapter implements AdapterInterface
{
    public function process(Media $media, ?MediaTransformation $transformation = null): void
    {
    }
}
