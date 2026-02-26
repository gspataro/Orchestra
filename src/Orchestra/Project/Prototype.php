<?php

namespace Orchestra\Project;

use Orchestra\Project\Definition\Source\SourceCollection;
use Orchestra\Project\Schema\SchemaCollection;
use Orchestra\Project\Definition\MediaVariant\MediaVariantCollection;

final class Prototype
{
    public function __construct(
        private readonly SourceCollection $sources,
        private readonly SchemaCollection $schemas,
        private readonly MediaVariantCollection $mediaVariants,
        private readonly Config $configs
    ) {
    }

    public function configs(): Config
    {
        return $this->configs;
    }

    public function sources(): SourceCollection
    {
        return $this->sources;
    }

    public function schemas(): SchemaCollection
    {
        return $this->schemas;
    }

    public function mediaVariants(): MediaVariantCollection
    {
        return $this->mediaVariants;
    }
}
