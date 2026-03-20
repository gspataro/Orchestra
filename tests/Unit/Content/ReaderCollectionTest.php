<?php

use Orchestra\Content\Exception\ReaderFoundException;
use Orchestra\Content\Exception\ReaderNotFoundException;
use Orchestra\Content\Reader\TextReader;
use Orchestra\Content\ReadersCollection;

test('has() returns false for unknown tags', function () {
    expect((new ReadersCollection())->has('unknown'))->toBeFalse();
});

test('add() and has() work together', function () {
    $collection = new ReadersCollection();
    $collection->add('text', new TextReader());
    expect($collection->has('text'))->toBeTrue();
});

test('get() returns the registered reader', function () {
    $collection = new ReadersCollection();
    $reader = new TextReader();
    $collection->add('text', $reader);
    expect($collection->get('text'))->toBe($reader);
});

test('add() throws ReaderFoundException when tag already exists', function () {
    $collection = new ReadersCollection();
    $collection->add('text', new TextReader());
    expect(fn () => $collection->add('text', new TextReader()))->toThrow(ReaderFoundException::class);
});

test('get() throws ReaderNotFoundException for unknown tags', function () {
    expect(fn () => (new ReadersCollection())->get('missing'))->toThrow(ReaderNotFoundException::class);
});
