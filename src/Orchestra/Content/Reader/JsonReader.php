<?php

namespace Orchestra\Content\Reader;

use Orchestra\Content\ContentPayload;
use Orchestra\Project\Definition\Source\Source;

final class JsonReader extends BaseReader
{
    public function compile(Source $source): iterable
    {
        $body = json_decode(file_get_contents($source->path), true);

        yield $this->contentFromSource(
            $source,
            $body
        );
    }
}
