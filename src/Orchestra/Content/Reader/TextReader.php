<?php

namespace Orchestra\Content\Reader;

use Orchestra\Content\ContentPayload;
use Orchestra\Project\Source\ResolvedSource;

final class TextReader extends BaseReader
{
    public function compile(ResolvedSource $source): ContentPayload
    {
        $body = file_get_contents($source->path);

        return $this->contentFromSource($source, $body);
    }
}
