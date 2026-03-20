<?php

use Orchestra\Media\Media;
use Orchestra\Media\MediaRepository;

it('adds and retrieves media by relative path', function () {
    $repository = new MediaRepository();
    $media = new Media('/media/photo.jpg', 'photo.jpg', '/public/photo.jpg', 'image/jpeg');
    $repository->add($media);

    expect($repository->get('photo.jpg'))->toBe($media);
});

test('has() returns correct booleans', function () {
    $repository = new MediaRepository();
    $repository->add(new Media('/media/photo.jpg', 'photo.jpg', '/public/photo.jpg', 'image/jpeg'));

    expect($repository->has('photo.jpg'))->toBeTrue();
    expect($repository->has('missing.jpg'))->toBeFalse();
});

test('all() returns all media as a flat array', function () {
    $repository = new MediaRepository();

    $repository->add(new Media('/media/a.jpg', 'a.jpg', '/public/a.jpg', 'image/jpeg'));
    $repository->add(new Media('/media/b.jpg', 'b.jpg', '/public/b.jpg', 'image/jpeg'));

    expect($repository->all())->toHaveCount(2);
});

test('adding with the same relative path overwrites the previous entry', function () {
    $repository = new MediaRepository();

    $m1 = new Media('/media/photo.jpg', 'photo.jpg', '/public/photo.jpg', 'image/jpeg');
    $m2 = new Media('/media/photo-v2.jpg', 'photo.jpg', '/public/photo-v2.jpg', 'image/png');

    $repository->add($m1);
    $repository->add($m2);

    expect($repository->get('photo.jpg'))->toBe($m2);
    expect($repository->all())->toHaveCount(1);
});
