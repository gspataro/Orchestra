<?php

namespace Orchestra\Content\Reader;

use Orchestra\Content\ReaderInterface;
use Orchestra\Content\ContentPayload;
use Orchestra\Project\Source\ResolvedSource;

abstract class BaseReader implements ReaderInterface
{
    protected function generateContentId(ResolvedSource $source): mixed
    {
        return sha1($source->group . ':' . $source->relativePath);
    }

    protected function generateContentTag(ResolvedSource $source): mixed
    {
        $fileName = pathinfo($source->relativePath, PATHINFO_FILENAME);
        return $source->group . '.' . $fileName;
    }

    protected function contentFromSource(ResolvedSource $source, mixed $body, array $metadata = []): ContentPayload
    {
        return new ContentPayload(
            $this->generateContentId($source),
            $this->generateContentTag($source),
            $source->group,
            $source->path,
            $body,
            $metadata
        );
    }
}
