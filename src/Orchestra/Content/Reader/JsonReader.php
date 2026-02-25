<?php

namespace Orchestra\Content\Reader;

use Orchestra\Content\ContentPayload;
use Orchestra\Project\Source\ResolvedSource;

final class JsonReader extends BaseReader
{
    public function compile(ResolvedSource $source): iterable
    {
        $body = json_decode(file_get_contents($source->path), true);

        yield $this->contentFromSource(
            $source,
            $body
        );
    }
}
