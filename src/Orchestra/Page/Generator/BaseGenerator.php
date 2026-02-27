<?php

namespace Orchestra\Page\Generator;

use Orchestra\Content\ContentCollection;
use Orchestra\Project\Sitemap;
use Orchestra\Page\GeneratorInterface;
use Orchestra\Page\Page;
use Orchestra\Page\PagePayload;
use Orchestra\Project\Definition\Schema\Schema;

abstract class BaseGenerator implements GeneratorInterface
{
    /**
     * @param string $tag
     * @param string $permalink
     * @param array $contents
     * @param Schema $schema
     * @return PagePayload
     */
    protected function preparePayload(
        string $tag,
        string $permalink,
        array $contents,
        Schema $schema
    ): PagePayload {
        return new PagePayload(
            $tag,
            $permalink,
            $contents,
            $schema
        );
    }
}
