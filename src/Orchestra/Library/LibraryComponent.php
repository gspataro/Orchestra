<?php

namespace Orchestra\Library;

use GSpataro\DependencyInjection\Container;
use Orchestra\Library\Archive;
use Orchestra\Library\Reader\JsonReader;
use Orchestra\Library\ReadersCollection;
use Orchestra\Library\Reader\TextReader;
use Orchestra\Library\Reader\MarkdownReader;
use Orchestra\Application\Component;

final class LibraryComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('library.readers', function ($container, $args): object {
            return new ReadersCollection();
        });

        $container->add('library.archive', function ($container, $args): object {
            return new Archive();
        });
    }

    public function boot(Container $container): void
    {
        $readersCollection = $container->get('library.readers');

        $readersCollection->add('text', new TextReader(
            $container->get('library.archive')
        ));

        $readersCollection->add('markdown', new MarkdownReader(
            $container->get('library.archive'),
            $container->get('markdown.converter')
        ));

        $readersCollection->add('json', new JsonReader(
            $container->get('library.archive')
        ));
    }
}
