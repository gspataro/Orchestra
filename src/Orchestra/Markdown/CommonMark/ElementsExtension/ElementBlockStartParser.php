<?php

namespace Orchestra\Markdown\CommonMark\ElementsExtension;

use League\CommonMark\Parser\Block\BlockStart;
use League\CommonMark\Parser\Block\BlockStartParserInterface;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Parser\MarkdownParserStateInterface;

class ElementBlockStartParser implements BlockStartParserInterface
{
    public function tryStart(Cursor $cursor, MarkdownParserStateInterface $parserState): ?BlockStart
    {
        $line = $cursor->getLine();
        $indent = $cursor->getIndent();

        if ($indent >= 4) {
            return BlockStart::none();
        }

        if (!preg_match("/^::([a-zA-Z0-9_-]+)(.*)$/", $line, $matches)) {
            return BlockStart::none();
        }

        $name = $matches[1];
        $rawProps = trim($matches[2]);

        $props = $this->parseProps($rawProps);

        $block = new ElementBlockParser($name, $props);

        return BlockStart::of($block)->at($cursor);
    }

    /**
     * @param string $rawProps
     * @return array<string,mixed>
     */
    private function parseProps(string $rawProps): array
    {
        $props = [];

        if ($rawProps === '') {
            return $props;
        }

        preg_match_all('/([a-zA-Z0-9_-]+)="([^"]*)"/', $rawProps, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $props[$match[1]] = $match[2];
        }

        return $props;
    }
}
