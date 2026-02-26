<?php

namespace Orchestra\Project;

use Orchestra\Project\Definition\MediaVariant\MediaVariantCollection;
use Orchestra\Project\Definition\Schema\SchemaCollection;
use Orchestra\Project\Definition\Source\SourceCollection;

final readonly class CompilerContext
{
    public function __construct(
        public SourceCollection $sources,
        public SchemaCollection $schemas,
        public MediaVariantCollection $mediaVariants,
        public Config $configs
    ) {
    }
}
