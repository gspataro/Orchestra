<?php

namespace Orchestra\Console\Runtime;

use Orchestra\Assets\Media;
use Orchestra\Pipeline\BuildContext;

final class RuntimeMedia extends Runtime
{
    private readonly Media $media;

    public function main(): mixed
    {
        $this->media = $this->container->get('assets.media');

        $this->output->print('{bold}Generating media');

        $mediaFiles = glob($this->context->paths->media('*.{jpg,jpeg,png}'), GLOB_BRACE);

        foreach ($mediaFiles as $mediaFile) {
            $this->media->resizeMedia($mediaFile, $this->context->paths->output('media'));
        }

        return true;
    }
}
