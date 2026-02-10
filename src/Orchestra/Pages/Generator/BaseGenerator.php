<?php

namespace Orchestra\Pages\Generator;

use Orchestra\Project\Sitemap;
use Orchestra\Pages\Interface\GeneratorInterface;
use Orchestra\Pages\Page\Page;
use Orchestra\Pages\Page\PageCollection;
use Orchestra\Project\Schema\ResolvedSchema;

abstract class BaseGenerator implements GeneratorInterface
{
    public function __construct(
        protected readonly PageCollection $pages,
        protected readonly Sitemap $sitemap
    ) {
    }

    protected function createPage(
        string $tag,
        string $permalink,
        array $contents,
        ResolvedSchema $schema
    ): void {
        $this->pages->add(new Page(
            $tag,
            $permalink,
            $contents,
            $schema
        ));
    }
}
