<?php

use Orchestra\Content\Content;
use Orchestra\Page\Page;
use Orchestra\Page\PageCollection;
use Orchestra\Page\Schema;

function makeSchema(
    string $tag = 'page',
    string $slug = '/page',
    array $contents = [],
    string $source = 'posts',
    array $options = []
): Schema {
    return new Schema($tag, $contents, 'template.twig', 'once', $source, $slug, $options);
}

function makeContent(array $meta = [], string $path = '/posts/post.md', string $id = 'post-id'): Content
{
    return new Content($id, 'group.post', 'group', $path, 'body', $meta);
}

it('stores pages and retrieves by permalink', function () {
    $page = new Page('home', '/index', [], makeSchema());

    $collection = new PageCollection();
    $collection->add($page);

    expect($collection->get('/index'))->toBe($page);
});

it('returns null for unknown permalinks', function () {
    expect((new PageCollection())->get('/unknown'))->toBeNull();
});

it('is iterable', function () {
    $collection = new PageCollection();

    $collection->add(new Page('a', '/a', [], makeSchema()));
    $collection->add(new Page('b', '/b', [], makeSchema()));

    expect(iterator_to_array($collection))->toHaveCount(2);
});
