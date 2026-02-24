<?php

namespace Orchestra\Content\Reader;

use Orchestra\Content\Archive;
use League\CommonMark\ConverterInterface;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use Orchestra\Content\Content;
use Orchestra\Compiler\BuildContext;
use Orchestra\Project\Source\ResolvedSource;

final class MarkdownReader extends BaseReader
{
    public function __construct(
        protected readonly ConverterInterface $markdown
    ) {
    }

    protected function compiler(ResolvedSource $source): Content
    {
        $body = $this->markdown->convert(
            file_get_contents($source->path)
        );

        if ($body instanceof RenderedContentWithFrontMatter) {
            return $this->contentFromSource(
                $source,
                $body->getContent(),
                $body->getFrontMatter()
            );
        }

        return $this->contentFromSource(
            $source,
            $body->getContent()
        );
    }
}
