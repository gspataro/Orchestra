<?php

use Orchestra\Content\Content;
use Orchestra\Content\ContentRepository;

it('stores and retrieves content by group', function () {
    $repository = new ContentRepository();
    $content = new Content('id1', 'blog.post', 'blog', '/p', 'b', []);
    $repository->add($content);

    expect($repository->group('blog')->toArray())->toHaveCount(1);
});

test('all() returns every added item', function () {
    $repository = new ContentRepository();
    $repository->add(new Content('id1', 't', 'g1', '/p', 'b', []));
    $repository->add(new Content('id2', 't', 'g2', '/p', 'b', []));

    expect(count($repository->all()))->toBe(2);
});

test('group() returns an empty collection for unknown groups', function () {
    expect(count((new ContentRepository())->group('unknown')))->toBe(0);
});

test('later add() with same id overwrites previous', function () {
    $repository = new ContentRepository();
    $repository->add(new Content('id1', 't', 'g', '/p', 'first', []));
    $repository->add(new Content('id1', 't', 'g', '/p', 'second', []));

    expect($repository->all()->toArray()['id1']->body)->toBe('second');
});
