<?php

namespace Orchestra\Project\Schema;

use Orchestra\Content\ContentCollection;

final class ResolvedSchema
{
    /**
     * @param string $tag
     * @param ContentCollection|Content[] $contents
     * @param string $template
     * @param string $generator
     * @param string $source
     * @param string $builder
     * @param string $slug
     * @param array $options
     */
    public function __construct(
        public string $tag,
        public ContentCollection|array $contents,
        public string $template,
        public string $generator,
        public string $source,
        public string $builder,
        public string $slug,
        public array $options
    ) {
    }
}
