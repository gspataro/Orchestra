<?php

namespace Orchestra\Content\Factory;

use Orchestra\Content\Source;
use Orchestra\Project\Definition\Source\SourceDefinition;

final class SourceFactory
{
    public function fromDefinition(SourceDefinition $source, string $path, string $relativePath): Source
    {
        return new Source(
            $source->group,
            $source->reader,
            $path,
            $relativePath
        );
    }
}
