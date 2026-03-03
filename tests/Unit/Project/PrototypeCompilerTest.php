<?php

use Orchestra\Blueprint\Blueprint;
use Orchestra\Blueprint\BlueprintCompiler;
use Orchestra\Project\Factory\PrototypeFactory;
use Orchestra\Project\Prototype;
use Orchestra\Project\PrototypeCompiler;

it('produces a Prototype from a full blueprint', function () {
    $bp = new Blueprint();
    $bp->init([
        'website' => ['name' => 'Test Site', 'url' => 'https://test.com'],
        'contents' => ['blog' => 'markdown:posts/*.md'],
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
    $prototype = (new PrototypeCompiler(new PrototypeFactory()))->compile($namespaces);

    expect($prototype)->toBeInstanceOf(Prototype::class);
    expect($prototype->configs()->get('website.name'))->toBe('Test Site');
    expect(iterator_to_array($prototype->sources()))->toHaveCount(1);
    expect(iterator_to_array($prototype->schemas()))->toHaveCount(1);
});
