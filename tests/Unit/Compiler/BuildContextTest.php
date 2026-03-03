<?php

use Orchestra\Compiler\BuildContext;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Compiler\Paths;
use Orchestra\Project\CompilerContext;
use Orchestra\Project\Config;
use Orchestra\Project\Definition\MediaVariant\MediaVariantDefinitionCollection;
use Orchestra\Project\Definition\Schema\SchemaDefinitionCollection;
use Orchestra\Project\Definition\Source\SourceDefinitionCollection;
use Orchestra\Project\Factory\PrototypeFactory;
use Orchestra\Project\Sitemap;

it('initialises with default paths when none are provided', function () {
    $context = new BuildContext();
    expect($context->paths())->toBeInstanceOf(Paths::class);
});

it('accepts custom Paths', function () {
    $paths = new Paths('/custom/root');
    $context = new BuildContext($paths);
    expect($context->paths()->root)->toBe('/custom/root');
});

test('setContext() makes prototype, sitemap and options available', function () {
    $context = new BuildContext();
    $prototype = (new PrototypeFactory())->fromContext(new CompilerContext(
        new SourceDefinitionCollection(),
        new SchemaDefinitionCollection(),
        new MediaVariantDefinitionCollection(),
        new Config()
    ));

    $sitemap = new Sitemap();
    $options = new BuildOptions();

    $context->setContext($prototype, $sitemap, $options);

    expect($context->prototype())->toBe($prototype);
    expect($context->sitemap())->toBe($sitemap);
    expect($context->options())->toBe($options);
});
