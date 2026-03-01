<?php

namespace Orchestra\Compiler\Runtime;

use Orchestra\Media\MediaRepository;
use Orchestra\Media\AdapterCollection;
use Orchestra\Compiler\BuildOptions;

final class MediaRuntime extends Runtime
{
    private MediaRepository $media;
    private AdapterCollection $adapters;

    public function run(BuildOptions $options): bool
    {
        if ($options->skipMedia) {
            $this->output->warning('Skipping media generation');
            return true;
        }

        $this->output->info('Generating media');

        $this->media = $this->container->get('media.repository');
        $this->adapters = $this->container->get('media.adapters');

        foreach ($this->media->all() as $media) {
            $adapter = $this->adapters->getFor($media->mimeType ?? 'default');

            if (!$adapter) {
                continue;
            }

            if (!$media->hasTransformations()) {
                $adapter->process($media);
                continue;
            }

            foreach ($media->getTransformations() as $variant) {
                $adapter->process($media, $variant);
            }
        }

        return true;
    }
}
