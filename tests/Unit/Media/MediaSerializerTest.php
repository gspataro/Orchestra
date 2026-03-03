<?php

use Orchestra\Media\Cache\MediaSerializer;
use Orchestra\Media\Media;
use Orchestra\Media\MediaTransformation;
use Orchestra\Project\Definition\MediaVariant\MediaVariantDefinition;

it('serializes transformation names and deserializes them back', function () {
    $media = new Media('/media/photo.jpg', 'photo.jpg', '/public/photo.jpg', 'image/jpeg');
    $variant = new MediaVariantDefinition('thumb', null, []);
    $transformation = new MediaTransformation('thumb', 'photo-thumb.jpg', '/public/photo-thumb.jpg', $variant);
    $media->addTransformation($transformation);

    $serializer = new MediaSerializer();
    $result = $serializer->unserialize($serializer->serialize($media));

    expect($result)->toBe(['thumb']);
});
