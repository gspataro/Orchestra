<?php

namespace Orchestra\Content\Reader;

use Orchestra\Content\ContentPayload;
use Orchestra\Project\Source\ResolvedSource;

final class TextReader extends BaseReader
{
    protected function compiler(ResolvedSource $source): ContentPayload
    {
        $body = file_get_contents($source->path);

        return $this->contentFromSource($source, $body);
    }
}
