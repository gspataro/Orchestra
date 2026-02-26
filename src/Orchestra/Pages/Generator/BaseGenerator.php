<?php

namespace Orchestra\Pages\Generator;

use Orchestra\Content\ContentCollection;
use Orchestra\Project\Sitemap;
use Orchestra\Pages\GeneratorInterface;
use Orchestra\Pages\Page;
use Orchestra\Pages\PageCollection;
use Orchestra\Project\Schema\ResolvedSchema;

abstract class BaseGenerator implements GeneratorInterface
{
    public function __construct(
        protected readonly Sitemap $sitemap
    ) {
    }

    /**
     * @param string $tag
     * @param string $permalink
     * @param ContentCollection[] $contents
     * @param ResolvedSchema $schema
     * @return void
     */
    protected function createPage(
        string $tag,
        string $permalink,
        array $contents,
        ResolvedSchema $schema
    ): Page {
        return new Page(
            $tag,
            $permalink,
            $contents,
            $schema
        );
    }
}
