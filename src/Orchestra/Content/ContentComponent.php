<?php

namespace Orchestra\Content;

use GSpataro\DependencyInjection\Container;
use Orchestra\Content\Reader\JsonReader;
use Orchestra\Content\ReadersCollection;
use Orchestra\Content\Reader\TextReader;
use Orchestra\Content\Reader\MarkdownReader;
use Orchestra\Application\Component;
use Orchestra\Content\Factory\ContentFactory;
use Orchestra\Content\Factory\SourceFactory;
use Orchestra\Content\Reader\JsonCollectionReader;

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
    }

    public function boot(Container $container): void
    {
        $readersCollection = $container->get('content.readers');

        $readersCollection->add('text', new TextReader());

        $readersCollection->add('markdown', new MarkdownReader(
            $container->get('markdown.converter')
        ));

        $readersCollection->add('json', new JsonReader());
        $readersCollection->add('json_collection', new JsonCollectionReader());
    }
}
