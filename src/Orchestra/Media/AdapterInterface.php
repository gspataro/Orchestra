<?php

namespace Orchestra\Media;

use Orchestra\Media\Variant\Variant;

interface AdapterInterface
{
    public function process(Media $media, ?Variant $transformation = null): void;
}
