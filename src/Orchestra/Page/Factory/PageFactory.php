<?php

namespace Orchestra\Page\Factory;

use Orchestra\Page\Page;
use Orchestra\Page\PagePayload;
use Orchestra\Project\Sitemap;

final class PageFactory
{
    public function __construct(
        private readonly Sitemap $sitemap
    ) {
    }

    public function fromPayload(PagePayload $payload): Page
    {
        return new Page(
            $payload->tag,
            $this->sitemap->add($payload->tag, $payload->permalink),
            $payload->contents,
            $payload->schema
        );
    }
}
