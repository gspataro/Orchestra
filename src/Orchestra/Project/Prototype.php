<?php

namespace Orchestra\Project;

use Orchestra\Project\Definition\Source\SourceDefinitionCollection;
use Orchestra\Project\Definition\Schema\SchemaDefinitionCollection;
use Orchestra\Project\Definition\MediaVariant\MediaVariantDefinitionCollection;
use Orchestra\Project\Definition\Relationship\RelationshipDefinitionCollection;

final class Prototype
{
    public function __construct(
        private readonly SourceDefinitionCollection $sources,
        private readonly RelationshipDefinitionCollection $relationships,
        private readonly SchemaDefinitionCollection $schemas,
        private readonly MediaVariantDefinitionCollection $mediaVariants,
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

    public function relationships(): RelationshipDefinitionCollection
    {
        return $this->relationships;
    }

    public function schemas(): SchemaDefinitionCollection
    {
        return $this->schemas;
    }

    public function mediaVariants(): MediaVariantDefinitionCollection
    {
        return $this->mediaVariants;
    }
}
