<?php

namespace GSpataro\Application\Process;

use GSpataro\Project\Sitemap;
use SimpleXMLElement;

class ProcessSitemap extends Process
{
    public function __construct(
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

        $xml->asXml(DIR_OUTPUT . '/sitemap.xml');

        return true;
    }
}
