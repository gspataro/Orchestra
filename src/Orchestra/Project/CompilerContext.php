<?php

namespace Orchestra\Project;

use Orchestra\Project\Definition\MediaVariant\MediaVariantCollection;
use Orchestra\Project\Definition\Schema\SchemaCollection;
use Orchestra\Project\Definition\Source\SourceDefinitionCollection;

final readonly class CompilerContext
{
    public function __construct(
        public SourceDefinitionCollection $sources,
        public SchemaCollection $schemas,
        public MediaVariantCollection $mediaVariants,
        public Config $configs
    ) {
    }
}
