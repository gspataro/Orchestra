<?php

namespace Orchestra\Project;

use Orchestra\Project\Definition\MediaVariant\MediaVariantCollection;
use Orchestra\Project\Definition\Schema\SchemaDefinitionCollection;
use Orchestra\Project\Definition\Source\SourceDefinitionCollection;

final readonly class CompilerContext
{
    public function __construct(
        public SourceDefinitionCollection $sources,
        public SchemaDefinitionCollection $schemas,
        public MediaVariantCollection $mediaVariants,
        public Config $configs
    ) {
    }
}
