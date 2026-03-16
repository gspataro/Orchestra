<?php

use Orchestra\Content\ContentCollection;
use Orchestra\Page\Generator\LoopGenerator;

it('yields one payload per content item', function () {
    $collection = new ContentCollection([
        makeContent(['slug' => 'post-a'], id: 'id-a'),
        makeContent(['slug' => 'post-b'], id: 'id-b'),
    ]);

    $schema = makeSchema(slug: '/blog', source: 'posts', contents: ['posts' => $collection]);
    $payloads = iterator_to_array((new LoopGenerator())->generate($schema));

    expect($payloads)->toHaveCount(2);
});

it('builds permalink from schema slug + content slug', function () {
    $collection = new ContentCollection([makeContent(['slug' => 'hello-world'])]);
    $schema = makeSchema(slug: '/blog', source: 'posts', contents: ['posts' => $collection]);
    $payloads = iterator_to_array((new LoopGenerator())->generate($schema));

    expect($payloads[0]->permalink)->toBe('/blog/hello-world');
});

it('falls back to pathinfo filename when metadata slug is absent', function () {
    $collection = new ContentCollection([makeContent([], path: '/posts/my-article.md')]);
    $schema = makeSchema(slug: '/news', source: 'posts', contents: ['posts' => $collection]);
    $payloads = iterator_to_array((new LoopGenerator())->generate($schema));

    expect($payloads[0]->permalink)->toBe('/news/my-article');
});

it('yields nothing for an empty source', function () {
    $schema = makeSchema(source: 'posts', contents: ['posts' => new ContentCollection()]);
    $payloads = iterator_to_array((new LoopGenerator())->generate($schema));

    expect($payloads)->toBeEmpty();
});

it('yields nothing when source key is absent', function () {
    $schema = makeSchema(source: 'posts', contents: []);
    $payloads = iterator_to_array((new LoopGenerator())->generate($schema));

    expect($payloads)->toBeEmpty();
});

it('removes the source key and wraps content as "post" in payload', function () {
    $collection = new ContentCollection([makeContent(['slug' => 'my-post'])]);
    $schema = makeSchema(source: 'posts', contents: ['posts' => $collection]);
    $payloads = iterator_to_array((new LoopGenerator())->generate($schema));

    expect($payloads[0]->contents)->toHaveKey('post');
    expect($payloads[0]->contents)->not->toHaveKey('posts');
});

it('uses content id as the payload tag', function () {
    $collection = new ContentCollection([makeContent(['slug' => 'my-post'], id: 'article-42')]);
    $schema = makeSchema(source: 'posts', contents: ['posts' => $collection]);
    $payloads = iterator_to_array((new LoopGenerator())->generate($schema));

    expect($payloads[0]->tag)->toBe('article-42');
});
