<?php

namespace Orchestra\Content\Factory;

use Orchestra\Content\Content;
use Orchestra\Content\ContentPayload;

final class ContentFactory
{
    public function fromPayload(ContentPayload $payload): Content
    {
        return new Content(
            $payload->id,
            $payload->tag,
            $payload->group,
            $payload->path,
            $payload->body,
            $payload->metadata
        );
    }
}
