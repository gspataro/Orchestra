<?php

use Orchestra\Page\Factory\PageFactory;
use Orchestra\Page\Page;
use Orchestra\Page\PagePayload;
use Orchestra\Project\Sitemap;

it('creates a Page from a PagePayload and registers path in sitemap', function () {
    $sitemap = new Sitemap();
    $factory = new PageFactory($sitemap);
    $schema = makeSchema();
    $payload = new PagePayload('home', '/index', [], $schema);

    $page = $factory->fromPayload($payload);

    expect($page)->toBeInstanceOf(Page::class);
    expect($page->tag)->toBe('home');
    expect($page->permalink)->toBe('/index');
    expect($sitemap->get('home'))->toBe('/index');
});

it('generates a unique permalink for duplicate paths', function () {
    $sitemap = new Sitemap();
    $factory = new PageFactory($sitemap);
    $schema = makeSchema();

    $factory->fromPayload(new PagePayload('home', '/index', [], $schema));
    $page2 = $factory->fromPayload(new PagePayload('home-copy', '/index', [], $schema));

    expect($page2->permalink)->toBe('/index-copy');
});
