<?php

namespace Orchestra\Compiler\Runtime;

use Orchestra\Media\MediaRepository;
use Orchestra\Media\AdapterCollection;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Media\Cache\MediaCacheRepository;
use Orchestra\Media\Cache\MediaIndexRepository;
use Orchestra\Media\MediaResolver;

final class MediaRuntime extends Runtime
{
    private MediaRepository $media;
    private AdapterCollection $adapters;
    private MediaIndexRepository $index;
    private MediaResolver $resolver;
    private MediaCacheRepository $cache;

    public function run(BuildOptions $options): bool
    {
        if ($options->skipMedia) {
            $this->output->warning('Skipping media generation');
            return true;
        }

        $this->output->info('Generating media');

        $this->resolver = $this->container->get('media.resolver');
        $this->media = $this->container->get('media.repository');
        $this->adapters = $this->container->get('media.adapters');
        $this->index = $this->container->get('media.cache.index');
        $this->cache = $this->container->get('media.cache');

        if ($requests = $this->index->load()) {
            $this->resolver->bulkRequest($requests);
        }

        foreach ($this->media->all() as $media) {
            $cached = $this->cache->load($media) ?? [];
            $this->cache->save($media);

            $adapter = $this->adapters->getFor($media->mimeType ?: 'default');

            if (!$adapter) {
                $this->output->warning(
                    "Cannot process '{$media->mimeType}' files."
                );
                continue;
            }

            if (!$media->hasTransformations() && $cached) {
                if (!is_file($media->publicPath)) {
                    $adapter->process($media);
                }
                continue;
            }

            foreach ($media->getTransformations() as $variant) {
                if (in_array($variant->name, $cached) && is_file($variant->publicPath)) {
                    continue;
                }

                $adapter->process($media, $variant);
            }
        }

        $this->index->save($this->media);

        return true;
    }
}
