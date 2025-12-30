<?php

namespace GSpataro\Application\Process;

use GSpataro\Assets\Media;

class ProcessMedia extends Process
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
