<?php

namespace Orchestra\Content\Reader;

use Orchestra\Content\ReaderInterface;
use Orchestra\Content\ContentPayload;
use Orchestra\Content\Source;

abstract class BaseReader implements ReaderInterface
{
    protected function generateContentId(Source $source): mixed
    {
        return sha1($source->group . ':' . $source->relativePath);
    }

    protected function generateContentTag(Source $source): mixed
    {
        $fileName = pathinfo($source->relativePath, PATHINFO_FILENAME);
        return $source->group . '.' . $fileName;
    }

    /**
     * @param Source $source
     * @param mixed $body
     * @param array<string|int,mixed> $metadata
     * @return ContentPayload
     */
    protected function contentFromSource(Source $source, mixed $body, array $metadata = []): ContentPayload
    {
        return new ContentPayload(
            $body,
            $metadata,
            $source
        );
    }
}
