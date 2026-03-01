<?php

namespace Orchestra\Content\Cache;

use Orchestra\Content\ContentPayload;

final class ContentPayloadSerializer
{
    public function serialize(ContentPayload $payload): string
    {
        return serialize([
            'metadata' => $payload->metadata,
            'body' => $payload->body
        ]);
    }

    /**
     * @param string $data
     * @return array<array<string,mixed>>
     */
    public function unserialize(string $data): array
    {
        return unserialize($data);
    }
}
