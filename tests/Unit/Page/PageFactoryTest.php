<?php

use Orchestra\Page\Factory\PageFactory;
use Orchestra\Page\Page;
use Orchestra\Page\PagePayload;
use Orchestra\Sitemap\Factory\SitemapResourceFactory;
use Orchestra\Sitemap\Permalink;
use Orchestra\Sitemap\Sitemap;

it('creates a Page from a PagePayload and registers path in sitemap', function () {
    $sitemap = new Sitemap();
    $permalink = new Permalink();
    $sitemapResourceFactory = new SitemapResourceFactory();
    $factory = new PageFactory($sitemap, $permalink, $sitemapResourceFactory);
    $schema = makeSchema();
    $payload = new PagePayload('home', '/index', [], $schema);

    $page = $factory->fromPayload($payload);

    expect($page)->toBeInstanceOf(Page::class);
    expect($page->tag)->toBe('home');
    expect($page->permalink)->toBe('/index');
    expect($sitemap->get('home')->permalink)->toBe('/index');
});

it('generates a unique permalink for duplicate paths', function () {
    $sitemap = new Sitemap();
    $permalink = new Permalink();
    $sitemapResourceFactory = new SitemapResourceFactory();
    $factory = new PageFactory($sitemap, $permalink, $sitemapResourceFactory);
    $schema = makeSchema();

    $factory->fromPayload(new PagePayload('home', '/index', [], $schema));
    $page2 = $factory->fromPayload(new PagePayload('home-copy', '/index', [], $schema));

    expect($page2->permalink)->toBe('/index-copy');
});
