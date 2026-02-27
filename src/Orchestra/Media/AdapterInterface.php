<?php

namespace Orchestra\Media;

use Orchestra\Media\MediaTransformation;

interface AdapterInterface
{
    /**
     * @return string[]
     */
    public function supports(): array;

    public function process(Media $media, ?MediaTransformation $transformation = null): void;
}
