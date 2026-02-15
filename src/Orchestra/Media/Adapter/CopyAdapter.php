<?php

namespace Orchestra\Media\Adapter;

use Orchestra\Media\Media;
use Orchestra\Media\AdapterInterface;
use Orchestra\Project\MediaVariant\MediaTransformation;

class CopyAdapter implements AdapterInterface
{
    public function process(Media $media, ?MediaTransformation $transformation = null): void
    {
        $dirname = pathinfo($media->publicPath, PATHINFO_DIRNAME);

        if (!is_dir($dirname)) {
            mkdir($dirname, 0777, true);
        }

        copy($media->path, $media->publicPath);
    }
}
