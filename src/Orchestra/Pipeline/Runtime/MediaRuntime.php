<?php

namespace Orchestra\Pipeline\Runtime;

use Orchestra\Media\MediaRepository;
use Orchestra\Media\AdapterCollection;

final class MediaRuntime extends Runtime
{
    private readonly MediaRepository $media;
    private readonly AdapterCollection $adapters;

    public function run(array $options = []): bool
    {
        if ($options['view-only'] !== null) {
            return true;
        }

        $this->output->info('Generating media');

        /** @var MediaRepository */
        $this->media = $this->container->get('media.repository');

        /** @var AdapterCollection */
        $this->adapters = $this->container->get('media.adapters');

        foreach ($this->media->all() as $media) {
            $mimeType = mime_content_type($media->path);

            if (!$mimeType) {
                continue;
            }

            $adapter = $this->adapters->getFor($mimeType);

            if (!$media->hasVariants()) {
                $adapter->process($media);
            }

            foreach ($media->getVariants() as $variant) {
                $adapter->process($media, $variant);
            }
        }

        return true;
    }
}
