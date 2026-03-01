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

    public function compile(Source $source): ContentPayload
    {
        $defaultMetadata = [
            'slug' => pathinfo($source->path, PATHINFO_FILENAME)
        ];

        $body = $this->markdown->convert(
            file_get_contents($source->path)
        );

        if ($body instanceof RenderedContentWithFrontMatter) {
            return $this->contentFromSource(
                $source,
                $body->getContent(),
                array_merge(
                    $defaultMetadata,
                    $body->getFrontMatter()
                )
            );
        }

        return $this->contentFromSource(
            $source,
            $body->getContent(),
            $defaultMetadata
        );
    }
}
