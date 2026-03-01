<?php

namespace Orchestra\Media\Cache;

use Orchestra\Cache\CacheStorageInterface;
use Orchestra\Media\Media;

final class MediaCacheRepository
{
    public function __construct(
        private readonly CacheStorageInterface $storage,
        private readonly MediaSerializer $serializer,
        private readonly MediaSignatureGenerator $signature
    ) {
    }

    /**
     * @param Media $media
     * @return string[]|null
     */
    public function load(Media $media): ?array
    {
        $signature = $this->signature->generateFromMedia($media);

        if (!$this->storage->has('media', $signature)) {
            return null;
        }

        return $this->serializer->unserialize(
            $this->storage->get('media', $signature)
        );
    }

    public function save(Media $media): void
    {
        $signature = $this->signature->generateFromMedia($media);

        $this->storage->save(
            'media',
            $signature,
            $this->serializer->serialize($media)
        );
    }
}
