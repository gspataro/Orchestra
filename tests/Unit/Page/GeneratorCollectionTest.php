<?php

use Orchestra\Page\Exception\GeneratorFoundException;
use Orchestra\Page\Exception\GeneratorNotFoundException;
use Orchestra\Page\Generator\OnceGenerator;
use Orchestra\Page\GeneratorCollection;

it('registers and retrieves generators', function () {
    $collection = new GeneratorCollection();
    $generator = new OnceGenerator();

    $collection->add('once', $generator);

    expect($collection->get('once'))->toBe($generator);
});

it('throws GeneratorFoundException on duplicate tag', function () {
    $collection = new GeneratorCollection();
    $collection->add('once', new OnceGenerator());

    expect(fn () => $collection->add('once', new OnceGenerator()))->toThrow(GeneratorFoundException::class);
});

it('throws GeneratorNotFoundException for missing tags', function () {
    expect(fn () => (new GeneratorCollection())->get('missing'))->toThrow(GeneratorNotFoundException::class);
});

it('has() returns correct booleans', function () {
    $collection = new GeneratorCollection();
    $collection->add('once', new OnceGenerator());

    expect($collection->has('once'))->toBeTrue();
    expect($collection->has('loop'))->toBeFalse();
});
