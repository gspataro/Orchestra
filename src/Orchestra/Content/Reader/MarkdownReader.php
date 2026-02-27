<?php

namespace Orchestra\Content\Reader;

use League\CommonMark\ConverterInterface;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use Orchestra\Content\ContentPayload;
use Orchestra\Content\Source;

final class MarkdownReader extends BaseReader
{
    public function __construct(
        protected readonly ConverterInterface $markdown
    ) {
    }

    public function compile(Source $source): iterable
    {
        $body = $this->markdown->convert(
            file_get_contents($source->path)
        );

        if ($body instanceof RenderedContentWithFrontMatter) {
            yield $this->contentFromSource(
                $source,
                $body->getContent(),
                $body->getFrontMatter()
            );
            return;
        }

        yield $this->contentFromSource(
            $source,
            $body->getContent()
        );
    }
}
