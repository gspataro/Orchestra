<?php

namespace Orchestra\Content\Cache;

use Orchestra\Cache\CacheStorageInterface;
use Orchestra\Content\ContentPayload;
use Orchestra\Content\Source;

final class ContentCacheRepository
{
    public function __construct(
        private readonly CacheStorageInterface $storage,
        private readonly ContentPayloadSerializer $serializer,
        private readonly SourceSignatureGenerator $signature
    ) {
    }

    public function load(Source $source): ?ContentPayload
    {
        $signature = $this->signature->generateFromSource($source);

        if (!$this->storage->has('content', $signature)) {
            return null;
        }

        $data = $this->serializer->unserialize(
            $this->storage->get('content', $signature)
        );

        return new ContentPayload(
            $data['body'],
            $data['metadata'],
            $source
        );
    }

    /**
     * @param Source $source
     * @param ContentPayload $payload
     * @return void
     */
    public function save(Source $source, ContentPayload $payload): void
    {
        $signature = $this->signature->generateFromSource($source);

        $this->storage->save(
            'content',
            $signature,
            $this->serializer->serialize($payload)
        );
    }
}
