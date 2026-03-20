<?php

use Orchestra\Media\Adapter\CopyAdapter;
use Orchestra\Media\AdapterCollection;

it('registers an adapter and maps its supported mime types', function () {
    $collection = new AdapterCollection();
    $collection->add(new CopyAdapter()); // supports 'fallback'

    expect($collection->getFor('fallback'))->toBeInstanceOf(CopyAdapter::class);
});

it('returns the fallback adapter for unknown mime types', function () {
    $collection = new AdapterCollection();
    $collection->add(new CopyAdapter());

    expect($collection->getFor('application/octet-stream'))->toBeInstanceOf(CopyAdapter::class);
});

it('returns null when no adapter matches and no fallback is registered', function () {
    $collection = new AdapterCollection();
    expect($collection->getFor('image/jpeg'))->toBeNull();
});

it('does not register the same adapter class twice', function () {
    $collection = new AdapterCollection();
    $collection->add(new CopyAdapter());
    $collection->add(new CopyAdapter());

    // getFor('fallback') should still work — no crash on duplicate
    expect($collection->getFor('fallback'))->toBeInstanceOf(CopyAdapter::class);
});
