<?php

namespace Orchestra\Content\Reader;

use Orchestra\Content\ContentPayload;
use Orchestra\Content\Source;

final class TextReader extends BaseReader
{
    public function compile(Source $source): ContentPayload
    {
        $body = file_get_contents($source->path);

        return $this->contentFromSource(
            $source,
            $body,
            [
                'slug' => pathinfo($source->path, PATHINFO_FILENAME)
            ]
        );
    }
}
