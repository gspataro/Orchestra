<?php

namespace Orchestra\Content;

use GSpataro\DependencyInjection\Container;
use Orchestra\Content\Reader\JsonReader;
use Orchestra\Content\ReadersCollection;
use Orchestra\Content\Reader\TextReader;
use Orchestra\Content\Reader\MarkdownReader;
use Orchestra\Application\Component;
use Orchestra\Content\Cache\ContentCacheRepository;
use Orchestra\Content\Cache\ContentPayloadSerializer;
use Orchestra\Content\Cache\SourceSignatureGenerator;
use Orchestra\Content\Factory\ContentFactory;
use Orchestra\Content\Factory\SourceFactory;

final class ContentComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('content.readers', function ($c, $a): object {
            return new ReadersCollection();
        });

        $container->add('content.repository', function ($c, $a): object {
            return new ContentRepository();
        });

        $container->add('content.factory', function ($c, $a): object {
            return new ContentFactory();
        });

        $container->add('content.source.factory', function ($c, $a): object {
            return new SourceFactory();
        });

        $container->add('content.source.signature', function ($c, $a): object {
            return new SourceSignatureGenerator(
                $c->get('cache.signature')
            );
        });

        $container->add('content.payload.serializer', function ($c, $a): object {
            return new ContentPayloadSerializer();
        });

        $container->add('content.cache', function ($c, $a): object {
            return new ContentCacheRepository(
                $c->get('cache.storage'),
                $c->get('content.payload.serializer'),
                $c->get('content.source.signature')
            );
        });
    }

    public function boot(Container $container): void
    {
        $readersCollection = $container->get('content.readers');

        $readersCollection->add('text', new TextReader());
        $readersCollection->add('json', new JsonReader());

        $readersCollection->add('markdown', new MarkdownReader(
            $container->get('markdown.converter')
        ));
    }
}
