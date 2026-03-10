<?php

namespace Orchestra\Compiler\Runtime;

use Orchestra\Compiler\BuildOptions;
use SimpleXMLElement;

final class SitemapRuntime extends Runtime
{
    public function run(BuildOptions $options): bool
    {
        $this->output->info('Generating sitemap.xml');

        /** @var \Orchestra\Compiler\UrlGenerator */
        $urlGenerator = $this->container->get('compiler.url');

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset></urlset>');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        $excluded = ['/404'];

        foreach ($this->context->sitemap()->getAll() as $url) {
            if (str_ends_with($url, '/index')) {
                $url = substr($url, 0, strlen('index') * -1);
            }

            if (in_array($url, $excluded)) {
                continue;
            }

            $urlElement = $xml->addChild('url');
            $urlElement->addChild('loc', $urlGenerator->to($url));
            $urlElement->addChild('lastmod', date('c'));
        }

        $xml->asXml($this->context->paths()->output('sitemap.xml'));

        return true;
    }
}
