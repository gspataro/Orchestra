<?php

use Orchestra\Content\Content;
use Orchestra\Content\ContentPayload;
use Orchestra\Content\Factory\ContentFactory;
use Orchestra\Content\Source;

it('creates a Content from a ContentPayload', function () {
    $source = new Source('blog', 'text', '/posts/hello.md', 'hello.md');
    $payload = new ContentPayload('<p>Hello</p>', ['slug' => 'hello'], $source);

    $content = (new ContentFactory())->fromPayload($payload);

    expect($content)->toBeInstanceOf(Content::class);
    expect($content->group)->toBe('blog');
    expect($content->body)->toBe('<p>Hello</p>');
    expect($content->metadata['slug'])->toBe('hello');
});

it('generates id as sha1 of group:relativePath', function () {
    $source = new Source('blog', 'text', '/posts/hello.md', 'hello.md');
    $payload = new ContentPayload('body', [], $source);
    $content = (new ContentFactory())->fromPayload($payload);

    expect($content->id)->toBe(sha1('blog:hello.md'));
});

it('generates tag as group.filename', function () {
    $source = new Source('blog', 'text', '/posts/my-post.md', 'my-post.md');
    $payload = new ContentPayload('body', [], $source);
    $content = (new ContentFactory())->fromPayload($payload);

    expect($content->tag)->toBe('blog.my-post');
});
