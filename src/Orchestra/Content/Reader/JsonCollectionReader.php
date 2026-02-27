<?php

namespace Orchestra\Content\Reader;

use Orchestra\Content\ContentPayload;
use Orchestra\Content\Source;

final class JsonCollectionReader extends BaseReader
{
    public function compile(Source $source): iterable
    {
        $data = json_decode(file_get_contents($source->path), true);

        foreach ($data as $body) {
            yield $this->contentFromSource(
                $source,
                $body
            );
        }
    }
}
