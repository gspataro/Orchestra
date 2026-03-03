<?php

use Orchestra\Page\Factory\SchemaFactory;
use Orchestra\Page\Schema;
use Orchestra\Project\Definition\Schema\SchemaDefinition;

it('creates a Schema from a SchemaDefinition', function () {
    $def = new SchemaDefinition('home', [], 'index.twig', 'once', '', 'twig', '/index', []);
    $schema = (new SchemaFactory())->fromDefinition($def, []);

    expect($schema)->toBeInstanceOf(Schema::class);
    expect($schema->tag)->toBe('home');
    expect($schema->template)->toBe('index.twig');
    expect($schema->slug)->toBe('/index');
});
