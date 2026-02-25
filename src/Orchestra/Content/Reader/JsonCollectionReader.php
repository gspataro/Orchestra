<?php

namespace Orchestra\Content\Reader;

use Orchestra\Content\ContentPayload;
use Orchestra\Project\Source\ResolvedSource;

final class JsonCollectionReader extends BaseReader
{
    protected function compiler(ResolvedSource $source): ContentPayload|array
    {
        $contents = [];
        $data = json_decode(file_get_contents($source->path), true);

        foreach ($data as $body) {
            $contents[] = $this->contentFromSource(
                $source,
                $body
            );
        }

        return $contents;
    }
}
