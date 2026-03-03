<?php

use Orchestra\Project\Sitemap;

it('adds a tag→path entry and retrieves it', function () {
    $sitemap = new Sitemap();
    $sitemap->add('home', '/index');

    expect($sitemap->get('home'))->toBe('/index');
});

test('has() returns true for existing tags', function () {
    $sitemap = new Sitemap();
    $sitemap->add('home', '/index');

    expect($sitemap->has('home'))->toBeTrue();
});

test('has() returns false for missing tags', function () {
    expect((new Sitemap())->has('missing'))->toBeFalse();
});

test('hasPath() returns true for existing paths', function () {
    $sitemap = new Sitemap();
    $sitemap->add('home', '/index');
    expect($sitemap->hasPath('/index'))->toBeTrue();
});

test('hasPath() returns false for missing paths', function () {
    expect((new Sitemap())->hasPath('/missing'))->toBeFalse();
});

test('add() returns the registered path', function () {
    $sitemap = new Sitemap();
    expect($sitemap->add('home', '/index'))->toBe('/index');
});

test('duplicate path triggers generateUniquePath and appends -copy', function () {
    $sitemap = new Sitemap();
    $sitemap->add('home', '/about');
    $path = $sitemap->add('duplicate', '/about');
    expect($path)->toBe('/about-copy');
    expect($sitemap->hasPath('/about-copy'))->toBeTrue();
});

test('multiple duplicates keep appending -copy', function () {
    $sitemap = new Sitemap();
    $sitemap->add('a', '/page');
    $sitemap->add('b', '/page');
    $path = $sitemap->add('c', '/page');
    expect($path)->toBe('/page-copy-copy');
});

test('getAll() returns every registered path', function () {
    $sitemap = new Sitemap();
    $sitemap->add('home', '/index');
    $sitemap->add('about', '/about');
    expect($sitemap->getAll())->toBe(['home' => '/index', 'about' => '/about']);
});

test('get() returns null for unknown tags', function () {
    expect((new Sitemap())->get('unknown'))->toBeNull();
});
