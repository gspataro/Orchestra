<?php

namespace GSpataro\Application\Component;

use GSpataro\DependencyInjection\Container;
use GSpataro\Library\Archive;
use GSpataro\Library\Reader\JsonReader;
use GSpataro\Library\ReadersCollection;
use GSpataro\Library\Reader\TextReader;
use GSpataro\Library\Reader\MarkdownReader;
use GSpataro\Solista\Component;

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
