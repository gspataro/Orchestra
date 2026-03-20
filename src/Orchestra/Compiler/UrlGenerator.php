<?php

namespace Orchestra\Compiler;

final class UrlGenerator
{
    private bool $friendlyUrls = true;
    private string $baseUrl = '';

    public function __construct(
        private readonly BuildContextProvider $context
    ) {
    }

    public function load(): void
    {
        $this->friendlyUrls = $this->context->get()->prototype()->configs()->get('website.friendly_urls');
        $this->baseUrl = $this->context->get()->options()->baseUrl
            ?? $this->context->get()->prototype()->configs()->get('website.url');
    }

    public function to(string $where): string
    {
        $suffix = $this->friendlyUrls ? '' : '.html';
        $resource = $this->context->get()->sitemap()->get($where);
        $path = !is_null($resource) ? $resource->permalink : $where;

        $separator = null;

        if (!str_ends_with($this->baseUrl, '/') && !str_starts_with($path, '/')) {
            $separator = '/';
        }

        if (!str_ends_with($path, '.html') && !$this->friendlyUrls) {
            $path .= $suffix;
        }

        if (str_ends_with($path, 'index' . $suffix)) {
            $path = substr($path, 0, strlen('index' . $suffix) * -1);
        }

        return $this->baseUrl . $separator . $path;
    }
}
