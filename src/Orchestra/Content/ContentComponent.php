<?php

namespace Orchestra\Content;

use GSpataro\DependencyInjection\Container;
use Orchestra\Content\Archive;
use Orchestra\Content\Reader\JsonReader;
use Orchestra\Content\ReadersCollection;
use Orchestra\Content\Reader\TextReader;
use Orchestra\Content\Reader\MarkdownReader;
use Orchestra\Application\Component;

final class ContentComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('content.readers', function ($container, $args): object {
            return new ReadersCollection();
        });

        $container->add('content.repository', function ($container, $args): object {
            return new ContentRepository();
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
    }
}
