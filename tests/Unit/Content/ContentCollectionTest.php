<?php

use Orchestra\Content\Content;
use Orchestra\Content\ContentCollection;

it('starts empty', function () {
    expect(count(new ContentCollection()))->toBe(0);
});

it('adds and counts content items', function () {
    $collection = new ContentCollection();
    $collection->add(new Content('id1', 't', 'g', '/p', 'b', []));
    $collection->add(new Content('id2', 't', 'g', '/p', 'b', []));

    expect(count($collection))->toBe(2);
});

it('is iterable', function () {
    $content = new Content('id1', 't', 'g', '/p', 'b', []);
    $collection = new ContentCollection([$content]);
    $items = iterator_to_array($collection);

    expect($items)->toHaveCount(1);
});

it('toArray() returns the underlying array', function () {
    $content = new Content('id1', 't', 'g', '/p', 'b', []);
    $collection = new ContentCollection([$content]);

    expect($collection->toArray())->toBe([$content->id => $content]);
});

it('offsetGet() throws OutOfBoundsException for missing offset', function () {
    $collection = new ContentCollection();

    expect(fn () => $collection[99])->toThrow(OutOfBoundsException::class);
});

it('offsetSet() is immutable', function () {
    $collection = new ContentCollection();
    $collection[0] = new Content('x', 't', 'g', '/p', 'b', []);
})->throws(\Orchestra\Content\Exception\ContentCollectionException::class);

it('query() returns a ContentQuery instance', function () {
    expect((new ContentCollection())->query())->toBeInstanceOf(\Orchestra\Content\ContentQuery::class);
});
