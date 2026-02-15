<?php

namespace Orchestra\Project;

use Orchestra\Project\Source\SourceCollection;
use Orchestra\Project\Schema\SchemaCollection;
use Orchestra\Project\MediaVariant\MediaVariantCollection;

final class Prototype
{
    public function __construct(
        private readonly SourceCollection $sources,
        private readonly SchemaCollection $schemas,
        private readonly MediaVariantCollection $mediaVariants
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

    public function getMediaVariants(): MediaVariantCollection
    {
        return $this->mediaVariants;
    }
}
