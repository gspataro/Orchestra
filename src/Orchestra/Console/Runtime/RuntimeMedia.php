<?php

namespace Orchestra\Console\Runtime;

use Orchestra\Assets\Media;
use Orchestra\Pipeline\BuildContext;

class RuntimeMedia extends Runtime
{
    public function __construct(
        private BuildContext $context,
        private Media $media
    ) {
    }

    public function main(): mixed
    {
        $this->output->print('{bold}Generating media');

        $mediaFiles = glob($this->context->paths->media('*.{jpg,jpeg,png}'), GLOB_BRACE);

        foreach ($mediaFiles as $mediaFile) {
            $this->media->resizeMedia($mediaFile, $this->context->paths->output('media'));
        }

        return true;
    }
}
