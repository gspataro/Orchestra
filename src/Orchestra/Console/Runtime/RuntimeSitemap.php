<?php

namespace Orchestra\Console\Runtime;

use Orchestra\Pipeline\BuildContext;
use Orchestra\Project\Sitemap;
use SimpleXMLElement;

class RuntimeSitemap extends Runtime
{
    public function __construct(
        private readonly BuildContext $context,
        private readonly Sitemap $sitemap
    ) {
    }

    public function main(): mixed
    {
        $this->output->print('{bold}Generating sitemap.xml');

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset></urlset>');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        $excluded = ['/404', '/darkside'];

        foreach ($this->sitemap->getAll() as $url) {
            if (str_ends_with($url, '/index')) {
                $url = substr($url, 0, strlen('index') * -1);
            }

            if (in_array($url, $excluded)) {
                continue;
            }

            $urlElement = $xml->addChild('url');
            $urlElement->addChild('loc', 'https://giuseppespataro.it' . $url);
            $urlElement->addChild('lastmod', date('c'));
        }

        $xml->asXml($this->context->paths->output('sitemap.xml'));

        return true;
    }
}
