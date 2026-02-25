<?php

namespace Orchestra\Content\Reader;

use Orchestra\Content\ContentPayload;
use Orchestra\Project\Source\ResolvedSource;

final class JsonReader extends BaseReader
{
    protected function compiler(ResolvedSource $source): ContentPayload
    {
        $body = json_decode(file_get_contents($source->path), true);

        return $this->contentFromSource(
            $source,
            $body
        );
    }
}
