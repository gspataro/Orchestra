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
        if ($payload->source->many) {
            return $payload->source->group . '.' . pathinfo($payload->source->relativePath, PATHINFO_FILENAME);
        } else {
            return $payload->source->group;
        }
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
