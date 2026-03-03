<?php

use Orchestra\Compiler\Paths;

it('builds default paths relative to the root', function () {
    $paths = new Paths('/var/www/project');
    $paths->setDefaults();

    expect($paths->output())->toBe('/var/www/project/public');
    expect($paths->data())->toBe('/var/www/project/contents/data');
    expect($paths->media())->toBe('/var/www/project/contents/media');
    expect($paths->cache())->toBe('/var/www/project/cache');
    expect($paths->themes())->toBe('/var/www/project/resources/themes');
});

it('appends extra path segments correctly', function () {
    $paths = new Paths('/var/www/project');
    $paths->setDefaults();

    expect($paths->output('media', 'photo.jpg'))->toBe('/var/www/project/public/media/photo.jpg');
    expect($paths->cache('orchestra', 'content'))->toBe('/var/www/project/cache/orchestra/content');
});

it('root() returns the project root', function () {
    $paths = new Paths('/var/www/project');

    expect($paths->root())->toBe('/var/www/project');
});
