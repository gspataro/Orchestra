<?php

namespace Orchestra\Media;

use Orchestra\Project\Definition\MediaVariant\MediaTransformation;

interface AdapterInterface
{
    /**
     * @return string[]
     */
    public function supports(): array;

    public function process(Media $media, ?MediaTransformation $transformation = null): void;
}
