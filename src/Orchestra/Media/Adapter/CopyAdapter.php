<?php

namespace Orchestra\Media\Adapter;

use Orchestra\Media\Media;
use Orchestra\Project\MediaVariant\MediaTransformation;

final class CopyAdapter extends BaseAdapter
{
    protected array $supports = [
        'fallback'
    ];

    public function handler(Media $media, ?MediaTransformation $transformation = null): void
    {
        copy($media->path, $media->publicPath);
    }
}
