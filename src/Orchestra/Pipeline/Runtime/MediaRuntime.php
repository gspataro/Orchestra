<?php

namespace Orchestra\Pipeline\Runtime;

use Orchestra\Assets\Media;

final class MediaRuntime extends Runtime
{
    private readonly Media $media;

    public function run(array $options = []): bool
    {
        if ($options['view-only'] !== null) {
            return true;
        }

        $this->output->info('Generating media');

        $this->media = $this->container->get('assets.media');

        $mediaFiles = glob($this->context->paths->media('*.{jpg,jpeg,png}'), GLOB_BRACE);

        foreach ($mediaFiles as $mediaFile) {
            $this->media->resizeMedia($mediaFile, $this->context->paths->output('media'));
        }

        return true;
    }
}
