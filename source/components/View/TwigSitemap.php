<?php

namespace GSpataro\View;

use Twig\TwigFunction;
use GSpataro\Project\Sitemap;
use GSpataro\Project\Blueprint;
use Twig\Extension\AbstractExtension;

final class TwigSitemap extends AbstractExtension
{
    public function __construct(
        private readonly Blueprint $blueprint,
        private readonly Sitemap $sitemap
    ) {
    }

    public function url($tag)
    {
        $url = $_ENV['WEBSITE_URL'] ?? '';
        $friendlyUrls = $this->blueprint->get('website.friendly_urls');
        $path = $this->sitemap->get($tag);
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

    public function media($file, $size)
    {
        $url = $_ENV['WEBSITE_URL'] ?? '';
        return $url . '/media/' . $size . '/' . $file;
    }

    public function getFunctions()
    {
        $functions = [];

        $functions[] = new TwigFunction('url', [$this, 'url']);
        $functions[] = new TwigFunction('media', [$this, 'media']);

        return $functions;
    }
}
