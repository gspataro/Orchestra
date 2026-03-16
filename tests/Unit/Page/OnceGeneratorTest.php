<?php

use Orchestra\Content\ContentCollection;
use Orchestra\Page\Generator\OnceGenerator;

it('yields exactly one PagePayload', function () {
    $schema = makeSchema(tag: 'home', slug: '/index');
    $payloads = iterator_to_array((new OnceGenerator())->generate($schema));

    expect($payloads)->toHaveCount(1);
});

test('payload carries the schema tag and slug as permalink', function () {
    $schema = makeSchema(tag: 'contact', slug: '/contact');
    $payloads = iterator_to_array((new OnceGenerator())->generate($schema));

    expect($payloads[0]->tag)->toBe('contact');
    expect($payloads[0]->permalink)->toBe('/contact');
});

test('payload contents match schema contents', function () {
    $collection = new ContentCollection([makeContent(['slug' => 'hero'])]);
    $schema = makeSchema(contents: ['hero' => $collection]);
    $payloads = iterator_to_array((new OnceGenerator())->generate($schema));

    expect($payloads[0]->contents['contents'])->toHaveKey('group.post');
});
