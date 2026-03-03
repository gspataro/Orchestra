<?php

use Orchestra\Media\Cache\MediaRepositorySerializer;
use Orchestra\Media\Media;
use Orchestra\Media\MediaRepository;
use Orchestra\Media\MediaTransformation;
use Orchestra\Project\Definition\MediaVariant\MediaVariantDefinition;

it('serializes a repository and round-trips correctly', function () {
    $repository = new MediaRepository();
    $media = new Media('/media/photo.jpg', 'photo.jpg', '/public/photo.jpg', 'image/jpeg');
    $variant = new MediaVariantDefinition('thumb', null, []);
    $transformation = new MediaTransformation('thumb', 'photo-thumb.jpg', '/public/photo-thumb.jpg', $variant);
    $media->addTransformation($transformation);
    $repository->add($media);

    $serializer = new MediaRepositorySerializer();
    $result = $serializer->unserialize($serializer->serialize($repository));

    expect($result)->toHaveKey('photo.jpg');
    expect($result['photo.jpg'])->toBe(['thumb']);
});
