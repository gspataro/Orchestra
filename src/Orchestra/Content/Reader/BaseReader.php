<?php

namespace Orchestra\Content\Reader;

use Orchestra\Content\ReaderInterface;
use Orchestra\Content\ContentPayload;
use Orchestra\Content\Source;

abstract class BaseReader implements ReaderInterface
{
    /**
     * @param Source $source
     * @param mixed $body
     * @param array<string|int,mixed> $metadata
     * @return ContentPayload
     */
    protected function contentFromSource(Source $source, mixed $body, array $metadata = []): ContentPayload
    {
        $fileName = pathinfo($source->path, PATHINFO_FILENAME);
        $defaultMetadata = [
            'slug' => $fileName,
            'draft' => str_starts_with($fileName, '_')
        ];

        return new ContentPayload(
            $body,
            array_merge($defaultMetadata, $metadata),
            $source
        );
    }
}
