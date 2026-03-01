<?php

namespace Orchestra\Media\Cache;

use Orchestra\Cache\CacheStorageInterface;
use Orchestra\Media\MediaRepository;

final class MediaIndexRepository
{
    public function __construct(
        private readonly CacheStorageInterface $storage,
        private readonly MediaRepositorySerializer $serializer
    ) {
    }

    public function load(): array
    {
        if (!$this->storage->has('media', 'index')) {
            return [];
        }

        return $this->serializer->unserialize(
            $this->storage->get('media', 'index')
        );
    }

    public function save(MediaRepository $repository): void
    {
        $this->storage->save(
            'media',
            'index',
            $this->serializer->serialize($repository)
        );
    }
}
