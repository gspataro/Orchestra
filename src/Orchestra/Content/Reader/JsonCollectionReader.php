<?php

namespace Orchestra\Content\Reader;

use Orchestra\Content\Content;
use Orchestra\Project\Source\ResolvedSource;

final class JsonCollectionReader extends BaseReader
{
    protected function compiler(ResolvedSource $source): Content|array
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
