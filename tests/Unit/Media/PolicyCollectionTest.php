<?php

use Orchestra\Media\PolicyCollection;

test('getFor() returns null for unknown mime types when no policy is registered', function () {
    expect((new PolicyCollection())->getFor('image/jpeg'))->toBeNull();
});

test('getFor() returns null for an unregistered mime type even when other policies exist', function () {
    $collection = new PolicyCollection();
    $collection->add(new \Orchestra\Media\Policy\ImagePolicy());

    expect($collection->getFor('video/mp4'))->toBeNull();
});

test('getFor() returns the correct policy for a registered mime type', function () {
    $collection = new PolicyCollection();
    $collection->add(new \Orchestra\Media\Policy\ImagePolicy());

    expect($collection->getFor('image/jpeg'))->toBeInstanceOf(\Orchestra\Media\Policy\ImagePolicy::class);
});
