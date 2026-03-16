<?php

use Orchestra\Compiler\BuildContext;
use Orchestra\Compiler\BuildContextProvider;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Compiler\Paths;
use Orchestra\Compiler\UrlGenerator;
use Orchestra\Project\CompilerContext;
use Orchestra\Project\Config;
use Orchestra\Project\Definition\MediaVariant\MediaVariantDefinitionCollection;
use Orchestra\Project\Definition\Schema\SchemaDefinitionCollection;
use Orchestra\Project\Definition\Source\SourceDefinitionCollection;
use Orchestra\Project\Factory\PrototypeFactory;
use Orchestra\Sitemap\Sitemap;
use Orchestra\Sitemap\SitemapResource;

function makeUrlContext(
    bool $friendlyUrls = true,
    string $configUrl = 'https://example.com',
    ?string $optionsBaseUrl = null,
    array $routes = []
): BuildContextProvider {
    $paths = Paths::builder('/project')->build();
    $context = new BuildContext($paths);

    $config = new Config();
    $config->set('website.friendly_urls', $friendlyUrls);
    $config->set('website.url', $configUrl);

    $prototype = (new PrototypeFactory())->fromContext(new CompilerContext(
        new SourceDefinitionCollection(),
        new SchemaDefinitionCollection(),
        new MediaVariantDefinitionCollection(),
        $config
    ));

    $sitemap = new Sitemap();

    foreach ($routes as $tag => $path) {
        $sitemap->add(new SitemapResource($tag, $path));
    }

    $options = new BuildOptions(baseUrl: $optionsBaseUrl);
    $context->setContext($prototype, $sitemap, $options);

    $contextProvider = new BuildContextProvider();
    $contextProvider->set($context);

    return $contextProvider;
}

describe('Friendly URLs on', function () {
    it('generates a simple URL without .html', function () {
        $generator = new UrlGenerator(makeUrlContext());
        $generator->load();

        expect($generator->to('about'))->toBe('https://example.com/about');
    });

    it('strips "index" suffix from paths', function () {
        $generator = new UrlGenerator(makeUrlContext());
        $generator->load();

        expect($generator->to('index'))->toBe('https://example.com/');
    });

    it('resolves paths through the sitemap', function () {
        $generator = new UrlGenerator(makeUrlContext(routes: ['home' => 'index']));
        $generator->load();

        expect($generator->to('home'))->toBe('https://example.com/');
    });
});

describe('Friendly URLs off', function () {
    it('appends .html to non-index pages', function () {
        $generator = new UrlGenerator(makeUrlContext(friendlyUrls: false));
        $generator->load();

        expect($generator->to('about'))->toBe('https://example.com/about.html');
    });

    it('strips index.html from path', function () {
        $generator = new UrlGenerator(makeUrlContext(friendlyUrls: false));
        $generator->load();

        expect($generator->to('index'))->toBe('https://example.com/');
    });

    it('does not double-add .html when path already ends with it', function () {
        $generator = new UrlGenerator(makeUrlContext(friendlyUrls: false));
        $generator->load();

        expect($generator->to('page.html'))->toBe('https://example.com/page.html');
    });
});

describe('Base URL resolution', function () {
    it('prefers BuildOptions::baseUrl over config url', function () {
        $generator = new UrlGenerator(makeUrlContext(
            configUrl: 'https://config.example.com',
            optionsBaseUrl: 'https://override.example.com'
        ));
        $generator->load();

        expect($generator->to('page'))->toStartWith('https://override.example.com');
    });

    it('falls back to config url when BuildOptions::baseUrl is null', function () {
        $generator = new UrlGenerator(makeUrlContext(configUrl: 'https://from-config.example.com'));
        $generator->load();

        expect($generator->to('page'))->toStartWith('https://from-config.example.com');
    });

    it('adds separator "/" when baseUrl does not end with / and path does not start with /', function () {
        $generator = new UrlGenerator(makeUrlContext(configUrl: 'https://example.com'));
        $generator->load();

        expect($generator->to('about'))->toBe('https://example.com/about');
    });

    it('does not double-slash when baseUrl already ends with /', function () {
        $generator = new UrlGenerator(makeUrlContext(configUrl: 'https://example.com/'));
        $generator->load();

        expect($generator->to('about'))->not->toContain('//about');
    });
});
