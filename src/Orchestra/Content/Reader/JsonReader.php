<?php

namespace Orchestra\Content\Reader;

use Orchestra\Content\ContentPayload;
use Orchestra\Content\Source;

final class JsonReader extends BaseReader
{
    public function compile(Source $source): ContentPayload
    {
        $body = json_decode(file_get_contents($source->path), true);
        $metadata = [
            'slug' => pathinfo($source->path, PATHINFO_FILENAME)
        ];

        if (isset($body['_metadata'])) {
            $metadata = array_merge($metadata, $body['_metadata']);
            unset($body['_metadata']);
        }

        return $this->contentFromSource(
            $source,
            $body,
            $metadata
        );
    }
}
