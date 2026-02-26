<?php

namespace Orchestra\Content\Reader;

use Orchestra\Content\ContentPayload;
use Orchestra\Project\Definition\Source\ResolvedSource;

final class TextReader extends BaseReader
{
    public function compile(ResolvedSource $source): iterable
    {
        $body = file_get_contents($source->path);

        yield $this->contentFromSource($source, $body);
    }
}
