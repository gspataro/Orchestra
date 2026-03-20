<?php

use Orchestra\Media\Factory\MediaTransformationFactory;
use Orchestra\Media\MediaTransformation;
use Orchestra\Project\Definition\MediaVariant\MediaVariantDefinition;

it('creates a MediaTransformation from a definition', function () {
    $definition = new MediaVariantDefinition('thumb', 'webp', ['width' => 150]);
    $transformation = (new MediaTransformationFactory())->fromDefinition($definition, '/public/media/photo-thumb.webp', 'photo-thumb.webp');

    expect($transformation)->toBeInstanceOf(MediaTransformation::class);
    expect($transformation->name)->toBe('thumb');
    expect($transformation->relativePath)->toBe('photo-thumb.webp');
    expect($transformation->publicPath)->toBe('/public/media/photo-thumb.webp');
    expect($transformation->variant)->toBe($definition);
});
