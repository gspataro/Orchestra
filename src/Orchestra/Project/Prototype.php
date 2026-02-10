<?php

namespace Orchestra\Project;

use Orchestra\Project\Source\SourceCollection;
use Orchestra\Project\Schema\SchemaCollection;

final class Prototype
{
    public function __construct(
        private readonly SourceCollection $sources,
        private readonly SchemaCollection $schemas
    ) {
    }

    public function getSources(): SourceCollection
    {
        return $this->sources;
    }

    public function getSchemas(): SchemaCollection
    {
        return $this->schemas;
    }
}
