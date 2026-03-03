<?php

use Orchestra\Content\Reader\TextReader;
use Orchestra\Content\Source;

it('compiles a text file and sets slug from filename', function () {
    $file = sys_get_temp_dir() . '/my-article.txt';
    file_put_contents($file, 'Hello World');

    $source = new Source('blog', 'text', $file, 'my-article.txt');
    $payload = (new TextReader())->compile($source);

    unlink($file);

    expect($payload->body)->toBe('Hello World');
    expect($payload->metadata['slug'])->toBe('my-article');
});
