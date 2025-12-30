<?php

namespace GSpataro\Pages\Generator;

use GSpataro\Pages\Pages;
use GSpataro\Library\Archive;
use GSpataro\Project\Sitemap;
use GSpataro\Pages\Interface\GeneratorInterface;

abstract class BaseGenerator implements GeneratorInterface
{
    /**
     * Initialize generator
     *
     * @param Pages $pages
     * @param Archive $archive
     * @param Sitemap $sitemap
     */

    public function __construct(
        protected readonly Pages $pages,
        protected readonly Archive $archive,
        protected readonly Sitemap $sitemap
    ) {
    }

    /**
     * Create page
     *
     * @param string $tag
     * @param string $permalink
     * @param string $template
     * @param string $builder
     * @param array $contents
     * @return void
     */

    protected function createPage(
        string $tag,
        string $permalink,
        string $template,
        string $builder,
        array $contents = []
    ): void {
        $this->pages->set($tag, [
            'permalink' => $permalink,
            'template' => $template,
            'builder' => $builder,
            'contents' => $contents
        ]);
    }

    /**
     * Create a collection of pages
     *
     * @param string $tag
     * @param string $template
     * @param string $builder
     * @param array $contents
     * @return void
     */

    protected function createCollection(
        string $tag,
        string $template,
        string $builder,
        array $contents = []
    ): void {
        $this->pages->set($tag, [
            'template' => $template,
            'builder' => $builder,
            'contents' => $contents,
            'collection' => []
        ]);
    }

    /**
     * Add a page to a collection
     *
     * @param string $collectionTag
     * @param string $pageTag
     * @param array $contents
     * @return void
     */

    protected function addPageToCollection(
        string $collectionTag,
        string $pageTag,
        string $permalink,
        array $contents = []
    ): void {
        $this->pages->set($collectionTag . '.collection.' . $pageTag, [
            'permalink' => $permalink,
            'contents' => $contents
        ]);
    }
}
