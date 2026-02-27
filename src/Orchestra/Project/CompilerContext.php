<?php

namespace Orchestra\Project;

use Orchestra\Project\Definition\MediaVariant\MediaVariantDefinitionCollection;
use Orchestra\Project\Definition\Schema\SchemaDefinitionCollection;
use Orchestra\Project\Definition\Source\SourceDefinitionCollection;

final readonly class CompilerContext
{
    public function __construct(
        public SourceDefinitionCollection $sources,
        public SchemaDefinitionCollection $schemas,
        public MediaVariantDefinitionCollection $mediaVariants,
        public Config $configs
    ) {
    }
}
