<?php

namespace Orchestra\Compiler;

final class UrlGenerator
{
    private readonly bool $friendlyUrls;
    private readonly string $baseUrl;

    public function __construct(
        private readonly BuildContext $context
    ) {
    }

    public function load(): void
    {
        $this->friendlyUrls = $this->context->prototype->configs()->get('website.friendly_urls');
        $this->baseUrl = $this->context->options->baseUrl
            ?? $this->context->prototype->configs()->get('website.url');
    }

    public function to(string $where): string
    {
        $suffix = $this->friendlyUrls ? '' : '.html';
        $path = $this->context->sitemap->get($where) ?? $where;
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
