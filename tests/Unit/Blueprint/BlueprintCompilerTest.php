<?php

use Orchestra\Blueprint\Blueprint;
use Orchestra\Blueprint\BlueprintCompiler;
use Orchestra\Blueprint\Namespace\ContentNamespace;
use Orchestra\Blueprint\Namespace\MediaNamespace;
use Orchestra\Blueprint\Namespace\SchemaNamespace;
use Orchestra\Blueprint\Namespace\WebsiteNamespace;
use Orchestra\Blueprint\Exception\InvalidBlueprintException;

it('compiles an empty blueprint to a NamespaceCollection', function () {
    $blueprint = new Blueprint();
    $blueprint->init([]);

    $namespaces = (new BlueprintCompiler())->compile($blueprint);

    expect($namespaces->get('website'))->toBeInstanceOf(WebsiteNamespace::class);
    expect($namespaces->get('contents'))->toBeInstanceOf(ContentNamespace::class);
    expect($namespaces->get('schemas'))->toBeInstanceOf(SchemaNamespace::class);
    expect($namespaces->get('media'))->toBeInstanceOf(MediaNamespace::class);
});

it('applies defaults for website fields', function () {
    $blueprint = new Blueprint();
    $blueprint->init([]);

    $namespaces = (new BlueprintCompiler())->compile($blueprint);
    $website = $namespaces->get('website');

    expect($website->get('name'))->toBe('Solista');
    expect($website->get('theme'))->toBe('pianoforte');
});

it('overrides website defaults with provided values', function () {
    $blueprint = new Blueprint();
    $blueprint->init([
        'website' => [
            'name' => 'Custom Site',
            'url' => 'https://custom.com'
        ]
    ]);

    $namespaces = (new BlueprintCompiler())->compile($blueprint);
    $website = $namespaces->get('website');

    expect($website->get('name'))->toBe('Custom Site');
    expect($website->get('url'))->toBe('https://custom.com');
});

it('throws InvalidBlueprintException when a required field is missing', function () {
    // The SchemaSpecification requires template and slug per schema entry
    $blueprint = new Blueprint();
    $blueprint->init([
        'schemas' => [
            'home' => [
                ['slug' => 'index']
            ]
        ]
    ]);

    // template is required — should throw
    expect(fn () => (new BlueprintCompiler())->compile($blueprint))
        ->toThrow(InvalidBlueprintException::class);
});

it('throws InvalidBlueprintException when a field has the wrong type', function () {
    $bp = new Blueprint();
    $bp->init([
        'website' => [
            'name' => 123 // name must be string
        ]
    ]);

    expect(fn () => (new BlueprintCompiler())->compile($bp))
        ->toThrow(InvalidBlueprintException::class);
});

it('compiles media sizes into MediaNamespace with default sizes', function () {
    $blueprint = new Blueprint();
    $blueprint->init([]);

    $namespaces = (new BlueprintCompiler())->compile($blueprint);
    $media = $namespaces->get('media');

    expect($media->get('images.sizes.thumbnail.width'))->toBe(150);
    expect($media->get('images.sizes.medium.width'))->toBe(500);
});


it('compiles a valid schemas namespace', function () {
    $bp = new Blueprint();
    $bp->init([
        'schemas' => [
            'home' => [
                'template' => 'index',
                'slug' => 'index',
                'contents' => [],
                'generate' => 'once',
                'builder' => 'twig',
                'source' => '',
                'options' => []
            ]
        ]
    ]);

    $namespaces = (new BlueprintCompiler())->compile($bp);
    $schemas = $namespaces->get('schemas');

    expect($schemas->get('home.template'))->toBe('index');
});
