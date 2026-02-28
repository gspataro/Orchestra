<?php

namespace Orchestra\Page\Factory;

use Orchestra\Page\Schema;
use Orchestra\Project\Definition\Schema\SchemaDefinition;

final class SchemaFactory
{
    /**
     * @param SchemaDefinition $definition
     * @param \Orchestra\Content\ContentCollection[] $contents
     * @return Schema
     */
    public function fromDefinition(SchemaDefinition $definition, array $contents): Schema
    {
        return new Schema(
            $definition->tag,
            $contents,
            $definition->template,
            $definition->generator,
            $definition->source,
            $definition->builder,
            $definition->slug,
            $definition->options
        );
    }
}
