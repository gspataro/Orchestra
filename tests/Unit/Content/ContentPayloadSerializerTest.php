<?php

use Orchestra\Content\Cache\ContentPayloadSerializer;
use Orchestra\Content\ContentPayload;
use Orchestra\Content\Source;

it('serializes and unserializes a payload round-trip', function () {
    $source = new Source('blog', 'text', '/post.txt', 'post.txt');
    $payload = new ContentPayload('<p>body</p>', ['slug' => 'post'], $source);

    $serializer = new ContentPayloadSerializer();
    $data = $serializer->unserialize($serializer->serialize($payload));

    expect($data['body'])->toBe('<p>body</p>');
    expect($data['metadata']['slug'])->toBe('post');
});
