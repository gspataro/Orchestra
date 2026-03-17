<?php

use Orchestra\Compiler\BuildContext;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Compiler\Paths;
use Orchestra\Project\CompilerContext;
use Orchestra\Project\Config;
use Orchestra\Project\Definition\MediaVariant\MediaVariantDefinitionCollection;
use Orchestra\Project\Definition\Relationship\RelationshipDefinitionCollection;
use Orchestra\Project\Definition\Schema\SchemaDefinitionCollection;
use Orchestra\Project\Definition\Source\SourceDefinitionCollection;
use Orchestra\Project\Factory\PrototypeFactory;
use Orchestra\Sitemap\Sitemap;

test('setContext() makes prototype, sitemap and options available', function () {
    $paths = Paths::builder('')->build();
    $context = new BuildContext($paths);

    $prototype = (new PrototypeFactory())->fromContext(new CompilerContext(
        new SourceDefinitionCollection(),
        new SchemaDefinitionCollection(),
        new RelationshipDefinitionCollection(),
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
