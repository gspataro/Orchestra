<?php

namespace Orchestra\Pages\Generator;

use Orchestra\Content\ContentCollection;
use Orchestra\Project\Sitemap;
use Orchestra\Pages\GeneratorInterface;
use Orchestra\Pages\Page;
use Orchestra\Pages\PagePayload;
use Orchestra\Project\Schema\ResolvedSchema;

abstract class BaseGenerator implements GeneratorInterface
{
    /**
     * @param string $tag
     * @param string $permalink
     * @param array $contents
     * @param ResolvedSchema $schema
     * @return PagePayload
     */
    protected function preparePayload(
        string $tag,
        string $permalink,
        array $contents,
        ResolvedSchema $schema
    ): PagePayload {
        return new PagePayload(
            $tag,
            $permalink,
            $contents,
            $schema
        );
    }
}
