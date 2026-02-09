<?php

namespace Orchestra\Console\Runtime;

use Orchestra\Assets\Media;

class RuntimeMedia extends Runtime
{
    public function __construct(
        private Media $media
    ) {
    }

    public function main(): mixed
    {
        $this->output->print('{bold}Generating media');

        $mediaFiles = glob(DIR_MEDIA . '/*.{jpg,jpeg,png}', GLOB_BRACE);

        foreach ($mediaFiles as $mediaFile) {
            $this->media->resizeMedia($mediaFile);
        }

        return true;
    }
}
