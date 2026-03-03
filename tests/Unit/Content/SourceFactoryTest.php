<?php

use Orchestra\Content\Factory\SourceFactory;
use Orchestra\Project\Definition\Source\SourceDefinition;

it('creates a Source from a SourceDefinition', function () {
    $def = new SourceDefinition('blog', 'markdown', 'posts/*.md');
    $source = (new SourceFactory())->fromDefinition($def, '/abs/posts/hello.md', 'posts/hello.md');

    expect($source->group)->toBe('blog');
    expect($source->reader)->toBe('markdown');
    expect($source->path)->toBe('/abs/posts/hello.md');
    expect($source->relativePath)->toBe('posts/hello.md');
});
