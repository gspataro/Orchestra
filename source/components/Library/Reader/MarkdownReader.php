<?php

namespace GSpataro\Library\Reader;

use GSpataro\Library\Archive;
use League\CommonMark\ConverterInterface;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;

final class MarkdownReader extends BaseReader
{
    /**
     * Initialize Markdown reader
     *
     * @param Archive $archive
     * @param ConverterInterface $markdown
     */

    public function __construct(
        protected readonly Archive $archive,
        protected readonly ConverterInterface $markdown
    ) {
    }

    /**
     * Handle a single file
     *
     * @param string $source
     * @return mixed
     */

    protected function compiler(string $source): mixed
    {
        $result = $this->markdown->convert(
            file_get_contents($this->getPath($source))
        );

        if ($result instanceof RenderedContentWithFrontMatter) {
            return [
                'frontmatter' => $result->getFrontMatter(),
                'content' => $result->getContent()
            ];
        }

        return $result->getContent();
    }
}
