<?php

use Orchestra\Content\Reader\JsonReader;
use Orchestra\Content\Source;

it('compiles a JSON file into body and sets slug from filename', function () {
    $file = sys_get_temp_dir() . '/settings.json';
    file_put_contents($file, json_encode(['key' => 'value']));

    $source = new Source('data', 'json', $file, 'settings.json');
    $payload = (new JsonReader())->compile($source);

    unlink($file);

    expect($payload->body['key'])->toBe('value');
    expect($payload->metadata['slug'])->toBe('settings');
});

it('extracts _metadata key and merges into metadata', function () {
    $file = sys_get_temp_dir() . '/post.json';
    file_put_contents($file, json_encode(['_metadata' => ['title' => 'My Post'], 'content' => 'text']));

    $source = new Source('blog', 'json', $file, 'post.json');
    $payload = (new JsonReader())->compile($source);

    unlink($file);

    expect($payload->metadata['title'])->toBe('My Post');
    expect(isset($payload->body['_metadata']))->toBeFalse();
    expect($payload->body['content'])->toBe('text');
});
