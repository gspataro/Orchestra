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

        if (empty($this->media->all())) {
            return true;
        }

        foreach ($this->media->all() as $media) {
            $adapter = $this->adapters->getFor($media->mimeType ?? 'default');

            if (!$adapter) {
                continue;
            }

            if (!$media->hasTransformations()) {
                $adapter->process($media);
            }

            foreach ($media->getTransformations() as $variant) {
                $adapter->process($media, $variant);
            }
        }

        return true;
    }
}
