<?php

namespace Orchestra\Sitemap;

final readonly class SitemapResource
{
    public function __construct(
        public string $tag,
        public string $permalink,
        public ?string $sourcePath = null
    ) {
    }
}
