<?php

namespace Orchestra\Media;

use Orchestra\Project\MediaVariant\MediaTransformation;

interface AdapterInterface
{
    public function process(Media $media, ?MediaTransformation $transformation = null): void;
}
