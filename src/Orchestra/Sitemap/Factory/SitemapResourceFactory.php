<?php

namespace Orchestra\Sitemap\Factory;

use Orchestra\Page\PagePayload;
use Orchestra\Sitemap\SitemapResource;

final class SitemapResourceFactory
{
    public function fromPagePayload(PagePayload $payload, string $permalink): SitemapResource
    {
        return new SitemapResource(
            $payload->tag,
            $permalink,
            $payload->sourcePath
        );
    }
}
