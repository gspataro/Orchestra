<?php

namespace Orchestra\Sitemap\Factory;

use Orchestra\Page\PagePayload;
use Orchestra\Sitemap\SitemapResource;

final class SitemapResourceFactory
{
    public function fromPagePayload(PagePayload $payload): SitemapResource
    {
        return new SitemapResource(
            $payload->tag,
            $payload->permalink,
            $payload->sourcePath
        );
    }
}
