<?php

use Orchestra\Content\ContentCollection;
use Orchestra\Page\Generator\ArchiveGenerator;

it('yields one page when content count fits on a single page', function () {
    $items = [];

    for ($i = 1; $i <= 5; $i++) {
        $items[] = makeContent(['slug' => "post-{$i}"], id: "id-{$i}");
    }

    $collection = new ContentCollection($items);
    $schema = makeSchema(slug: '/blog', source: 'posts', contents: ['posts' => $collection], options: ['per_page' => 12]);
    $payloads = iterator_to_array((new ArchiveGenerator())->generate($schema));

    expect($payloads)->toHaveCount(1);
});

it('yields multiple pages when content overflows', function () {
    $items = [];

    for ($i = 1; $i <= 25; $i++) {
        $items[] = makeContent(['slug' => "post-{$i}"], id: "id-{$i}");
    }

    $collection = new ContentCollection($items);
    $schema = makeSchema(slug: '/blog', source: 'posts', contents: ['posts' => $collection], options: ['per_page' => 12]);
    $payloads = iterator_to_array((new ArchiveGenerator())->generate($schema));

    expect($payloads)->toHaveCount(3); // ceil(25/12) = 3
});

test('first page uses "index" as slug segment', function () {
    $collection = new ContentCollection([makeContent()]);
    $schema = makeSchema(slug: '/blog', source: 'posts', contents: ['posts' => $collection]);
    $payloads = iterator_to_array((new ArchiveGenerator())->generate($schema));

    expect($payloads[0]->permalink)->toBe('/blog/index');
});

test('second page uses page number as slug segment', function () {
    $items = [];

    for ($i = 1; $i <= 15; $i++) {
        $items[] = makeContent(['slug' => "post-{$i}"], id: "id-{$i}");
    }

    $collection = new ContentCollection($items);
    $schema = makeSchema(slug: '/blog', source: 'posts', contents: ['posts' => $collection], options: ['per_page' => 10]);
    $payloads = iterator_to_array((new ArchiveGenerator())->generate($schema));

    expect($payloads[1]->permalink)->toBe('/blog/2');
});

test('pagination contains correct prev/next references', function () {
    $items = [];

    for ($i = 1; $i <= 25; $i++) {
        $items[] = makeContent(['slug' => "post-{$i}"], id: "id-{$i}");
    }

    $collection = new ContentCollection($items);
    $schema = makeSchema(slug: '/blog', source: 'posts', contents: ['posts' => $collection], options: ['per_page' => 12]);
    $payloads = iterator_to_array((new ArchiveGenerator())->generate($schema));

    // Page 1: no prev, next = 2
    expect($payloads[0]->contents['archive']['pagination']['prev'])->toBeNull();
    expect($payloads[0]->contents['archive']['pagination']['next'])->toBe($schema->tag . '.page-2');

    // Page 2: prev = 1, next = 3
    expect($payloads[1]->contents['archive']['pagination']['prev'])->toBe($schema->tag . '.page-1');
    expect($payloads[1]->contents['archive']['pagination']['next'])->toBe($schema->tag . '.page-3');

    // Last page: no next
    expect(end($payloads)->contents['archive']['pagination']['next'])->toBeNull();
});

it('yields a single empty page when source is empty', function () {
    $schema = makeSchema(slug: '/blog', source: 'posts', contents: ['posts' => new ContentCollection()]);
    $payloads = iterator_to_array((new ArchiveGenerator())->generate($schema));

    expect($payloads)->toHaveCount(1);
});
