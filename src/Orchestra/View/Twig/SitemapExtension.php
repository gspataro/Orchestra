<?php

namespace Orchestra\View\Twig;

use Orchestra\Pipeline\BuildContext;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

final class SitemapExtension extends AbstractExtension
{
    public function __construct(
        private readonly BuildContext $context,
    ) {
    }

    public function url($tag)
    {
        $url = getenv('WEBSITE_URL') ?: '';
        $friendlyUrls = $this->context->blueprint->get('website.friendly_urls');
        $path = $this->context->sitemap->get($tag);
        $separator = null;
        $suffix = $friendlyUrls ? null : '.html';

        if (!$path) {
            $path = $tag;
        }

        if (!str_ends_with($url, '/') && !str_starts_with($path, '/')) {
            $separator = '/';
        }

        if (!str_ends_with($path, '.html') && !$friendlyUrls) {
            $path .= $suffix;
        }

        if (str_ends_with($path, 'index' . $suffix)) {
            $path = substr($path, 0, strlen('index' . $suffix) * -1);
        }

        return $url . $separator . $path;
    }

    public function getFunctions()
    {
        $functions = [];

        $functions[] = new TwigFunction('url', [$this, 'url']);

        return $functions;
    }
}
