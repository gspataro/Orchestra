<?php

namespace Orchestra\Page\Factory;

use Orchestra\Page\Page;
use Orchestra\Page\PagePayload;
use Orchestra\Sitemap\Sitemap;
use Orchestra\Sitemap\Permalink;
use Orchestra\Sitemap\Factory\SitemapResourceFactory;

final class PageFactory
{
    public function __construct(
        private readonly Sitemap $sitemap,
        private readonly Permalink $permalink,
        private readonly SitemapResourceFactory $sitemapResourceFactory
    ) {
    }

    public function fromPayload(PagePayload $payload): Page
    {
        $permalink = $this->permalink->generateUnique($payload->permalink);
        $sitemapResource = $this->sitemapResourceFactory->fromPagePayload($payload, $permalink);

        $this->sitemap->add($sitemapResource);

        return new Page(
            $payload->tag,
            $permalink,
            $payload->contents,
            $payload->schema
        );
    }
}
