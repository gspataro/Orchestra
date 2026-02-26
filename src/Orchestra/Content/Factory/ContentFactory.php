<?php

namespace Orchestra\Content\Factory;

use Orchestra\Content\Content;
use Orchestra\Content\ContentPayload;

final class ContentFactory
{
    private function generateId(ContentPayload $payload): string
    {
        return sha1($payload->source->group . ':' . $payload->source->relativePath);
    }

    private function generateTag(ContentPayload $payload): string
    {
        return $payload->source->group . '.' . pathinfo($payload->source->relativePath, PATHINFO_FILENAME);
    }

    public function fromPayload(ContentPayload $payload): Content
    {
        return new Content(
            $this->generateId($payload),
            $this->generateTag($payload),
            $payload->source->group,
            $payload->source->path,
            $payload->body,
            $payload->metadata
        );
    }
}
