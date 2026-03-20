<?php

use Orchestra\Media\Media;
use Orchestra\Media\MediaTransformation;
use Orchestra\Project\Definition\MediaVariant\MediaVariantDefinition;

function makeMedia(string $path = '/media/photo.jpg', string $rel = 'photo.jpg'): Media
{
    return new Media($path, $rel, '/public/media/photo.jpg', 'image/jpeg');
}

function makeVariant(string $name = 'thumb'): MediaVariantDefinition
{
    return new MediaVariantDefinition($name, 'webp', ['width' => 150]);
}

function makeTransformation(string $name = 'thumb'): MediaTransformation
{
    return new MediaTransformation($name, "photo-{$name}.webp", "/public/photo-{$name}.webp", makeVariant($name));
}

it('starts with no transformations', function () {
    expect(makeMedia()->hasTransformations())->toBeFalse();
});

test('addTransformation() registers a transformation', function () {
    $media = makeMedia();
    $media->addTransformation(makeTransformation('thumb'));

    expect($media->hasTransformations())->toBeTrue();
});

test('getTransformation() retrieves by name', function () {
    $media = makeMedia();
    $transformation = makeTransformation('medium');
    $media->addTransformation($transformation);

    expect($media->getTransformation('medium'))->toBe($transformation);
});

test('getTransformation() returns null for unknown names', function () {
    expect(makeMedia()->getTransformation('missing'))->toBeNull();
});

test('getTransformations() returns all registered transformations', function () {
    $media = makeMedia();
    $media->addTransformation(makeTransformation('thumb'));
    $media->addTransformation(makeTransformation('medium'));

    expect($media->getTransformations())->toHaveCount(2);
});

test('adding the same transformation object twice is idempotent', function () {
    $media = makeMedia();
    $transformation = makeTransformation('thumb');
    $media->addTransformation($transformation);
    $media->addTransformation($transformation);

    expect($media->getTransformations())->toHaveCount(1);
});

test('transformations are keyed by name', function () {
    $media = makeMedia();
    $media->addTransformation(makeTransformation('thumb'));

    expect(array_keys($media->getTransformations()))->toBe(['thumb']);
});
