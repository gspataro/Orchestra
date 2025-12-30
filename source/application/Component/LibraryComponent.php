<?php

namespace GSpataro\Application\Component;

use GSpataro\Library\Archive;
use GSpataro\Library\Researcher;
use GSpataro\Library\Reader\JsonReader;
use GSpataro\Library\ReadersCollection;
use GSpataro\Library\Reader\TextReader;
use GSpataro\Library\Reader\MarkdownReader;
use GSpataro\DependencyInjection\Component;

final class LibraryComponent extends Component
{
    public function register(): void
    {
        $this->container->add('library.readers', function ($container, $args): object {
            return new ReadersCollection();
        });

        $this->container->add('library.archive', function ($container, $args): object {
            return new Archive();
        });
    }

    public function boot(): void
    {
        $readersCollection = $this->container->get('library.readers');

        $readersCollection->add('text', new TextReader(
            $this->container->get('library.archive')
        ));

        $readersCollection->add('markdown', new MarkdownReader(
            $this->container->get('library.archive'),
            $this->container->get('markdown.converter')
        ));

        $readersCollection->add('json', new JsonReader(
            $this->container->get('library.archive')
        ));
    }
}
