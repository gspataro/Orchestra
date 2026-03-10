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

    public function load(Source $source, string $context = ''): ?ContentPayload
    {
        $signature = $this->signature->generateFromSource($source, $context);

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

    public function save(Source $source, ContentPayload $payload, string $context = ''): void
    {
        $signature = $this->signature->generateFromSource($source, $context);

        $this->storage->save(
            'content',
            $signature,
            $this->serializer->serialize($payload)
        );
    }
}
