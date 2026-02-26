<?php

namespace Orchestra\Content\Reader;

use Orchestra\Content\ContentPayload;
use Orchestra\Project\Definition\Source\ResolvedSource;

final class JsonCollectionReader extends BaseReader
{
    public function compile(ResolvedSource $source): iterable
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
