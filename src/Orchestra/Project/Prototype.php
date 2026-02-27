<?php

namespace Orchestra\Project;

use Orchestra\Project\Definition\Source\SourceDefinitionCollection;
use Orchestra\Project\Definition\Schema\SchemaDefinitionCollection;
use Orchestra\Project\Definition\MediaVariant\MediaVariantCollection;

final class Prototype
{
    public function __construct(
        private readonly SourceDefinitionCollection $sources,
        private readonly SchemaDefinitionCollection $schemas,
        private readonly MediaVariantCollection $mediaVariants,
        private readonly Config $configs
    ) {
    }

    public function configs(): Config
    {
        return $this->configs;
    }

    public function sources(): SourceDefinitionCollection
    {
        return $this->sources;
    }

    public function schemas(): SchemaDefinitionCollection
    {
        return $this->schemas;
    }

    public function mediaVariants(): MediaVariantCollection
    {
        return $this->mediaVariants;
    }
}
