<?php

namespace Orchestra\Content\Reader;

use Orchestra\Content\Content;
use Orchestra\Project\Source\ResolvedSource;

final class JsonReader extends BaseReader
{
    protected function compiler(ResolvedSource $source): Content
    {
        $body = json_decode(file_get_contents($source->path), true);

        return $this->contentFromSource(
            $source,
            $body
        );
    }
}
