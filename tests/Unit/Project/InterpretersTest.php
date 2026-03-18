<?php

use Orchestra\Blueprint\Blueprint;
use Orchestra\Blueprint\BlueprintCompiler;
use Orchestra\Project\CompilerContext;
use Orchestra\Project\Config;
use Orchestra\Project\Definition\MediaVariant\MediaVariantDefinition;
use Orchestra\Project\Definition\MediaVariant\MediaVariantDefinitionCollection;
use Orchestra\Project\Definition\Relationship\RelationshipDefinitionCollection;
use Orchestra\Project\Definition\Schema\SchemaDefinitionCollection;
use Orchestra\Project\Definition\Source\SourceDefinitionCollection;
use Orchestra\Project\Interpreter\ConfigInterpreter;
use Orchestra\Project\Interpreter\MediaInterpreter;
use Orchestra\Project\Interpreter\SchemaInterpreter;
use Orchestra\Project\Interpreter\SourceInterpreter;

function makeContext(): CompilerContext
{
    return new CompilerContext(
        new SourceDefinitionCollection(),
        new RelationshipDefinitionCollection(),
        new SchemaDefinitionCollection(),
        new MediaVariantDefinitionCollection(),
        new Config()
    );
}

describe('ConfigInterpreter', function () {
    it('sets website config from namespace', function () {
        $bp = new Blueprint();
        $bp->init(['website' => ['name' => 'My Site', 'url' => 'https://example.com']]);
        $namespaces = (new BlueprintCompiler())->compile($bp);

        $ctx = makeContext();
        (new ConfigInterpreter())->compile($namespaces->get('website'), $ctx);

        expect($ctx->configs->get('website.name'))->toBe('My Site');
    });

    it('has namespace() == "website"', function () {
        expect((new ConfigInterpreter())->namespace())->toBe('website');
    });
});

describe('SourceInterpreter', function () {
    it('parses "reader:path" source strings and registers SourceDefinitions', function () {
        $bp = new Blueprint();
        $bp->init([
            'contents' => [
                'blog' => [
                    'files' => 'posts/*.md',
                    'reader' => 'markdown'
                ],
                'pages' => [
                    'files' => 'posts/*.md',
                    'reader' => 'markdown'
                ]
            ]
        ]);
        $namespaces = (new BlueprintCompiler())->compile($bp);

        $ctx = makeContext();
        (new SourceInterpreter())->compile($namespaces->get('contents'), $ctx);

        $sources = iterator_to_array($ctx->sources);
        expect($sources)->toHaveCount(2);
        expect($sources[0]->group)->toBe('blog');
        expect($sources[0]->reader)->toBe('markdown');
        expect($sources[0]->path)->toBe('posts/*.md');
    });

    it('does nothing when contents namespace is empty', function () {
        $bp = new Blueprint();
        $bp->init([]);
        $namespaces = (new BlueprintCompiler())->compile($bp);
        $ctx = makeContext();
        (new SourceInterpreter())->compile($namespaces->get('contents'), $ctx);
        expect(iterator_to_array($ctx->sources))->toHaveCount(0);
    });
});

describe('SchemaInterpreter', function () {
    it('creates SchemaDefinitions for each schema entry', function () {
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
        $ctx = makeContext();
        (new SchemaInterpreter())->compile($namespaces->get('schemas'), $ctx);

        $schemas = iterator_to_array($ctx->schemas);
        expect($schemas)->toHaveCount(1);
        expect($schemas[0]->tag)->toBe('home');
        expect($schemas[0]->slug)->toBe('/index'); // sanitizeSlug prepends /
    });

    it('prepends / to slugs that are missing it', function () {
        $bp = new Blueprint();
        $bp->init([
            'schemas' => [
                'about' => [
                    'template' => 'about',
                    'slug' => 'about',
                    'contents' => [],
                    'generate' => 'once',
                    'builder' => 'twig',
                    'source' => '',
                    'options' => []
                ]
            ]
        ]);
        $namespaces = (new BlueprintCompiler())->compile($bp);
        $ctx = makeContext();
        (new SchemaInterpreter())->compile($namespaces->get('schemas'), $ctx);
        expect(iterator_to_array($ctx->schemas)[0]->slug)->toBe('/about');
    });

    it('does nothing when schemas namespace is empty', function () {
        $bp = new Blueprint();
        $bp->init([]);
        $namespaces = (new BlueprintCompiler())->compile($bp);
        $ctx = makeContext();
        (new SchemaInterpreter())->compile($namespaces->get('schemas'), $ctx);
        expect(iterator_to_array($ctx->schemas))->toHaveCount(0);
    });
});

describe('MediaInterpreter', function () {
    it('registers image variants into the context', function () {
        $bp = new Blueprint();
        $bp->init([
            'media' => [
                'images' => [
                    'optimize' => ['strategy' => 'webp'],
                    'sizes' => [
                        'thumb' => ['width' => 150, 'height' => 150, 'crop' => true, 'quality' => 80],
                    ],
                    'responsive' => ['thumb']
                ]
            ]
        ]);
        $namespaces = (new BlueprintCompiler())->compile($bp);
        $ctx = makeContext();
        (new MediaInterpreter())->compile($namespaces->get('media'), $ctx);

        $variant = $ctx->mediaVariants->image('thumb');
        expect($variant)->toBeInstanceOf(MediaVariantDefinition::class);
        expect($variant->format)->toBe('webp');
        expect($variant->option('width'))->toBe(150);
    });

    it('sets responsive config key', function () {
        $bp = new Blueprint();
        $bp->init([
            'media' => [
                'images' => [
                    'optimize' => ['strategy' => 'webp'],
                    'sizes' => ['sm' => ['width' => 400, 'height' => null, 'crop' => false, 'quality' => 80]],
                    'responsive' => ['sm']
                ]
            ]
        ]);
        $namespaces = (new BlueprintCompiler())->compile($bp);
        $ctx = makeContext();
        (new MediaInterpreter())->compile($namespaces->get('media'), $ctx);
        expect($ctx->configs->get('media.images.responsive'))->toBe(['sm']);
    });
});
