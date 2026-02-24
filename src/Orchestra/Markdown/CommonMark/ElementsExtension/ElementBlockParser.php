<?php

namespace Orchestra\Markdown\CommonMark\ElementsExtension;

use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Parser\Block\AbstractBlockContinueParser;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Parser\Block\BlockContinueParserInterface;
use League\CommonMark\Parser\Block\BlockContinue;

final class ElementBlockParser extends AbstractBlockContinueParser
{
    private ElementBlock $block;

    public function __construct(
        string $name,
        array $props
    ) {
        $this->block = new ElementBlock($name, $props);
    }

    public function getBlock(): AbstractBlock
    {
        return $this->block;
    }

    public function tryContinue(Cursor $cursor, BlockContinueParserInterface $activeBlockParser): ?BlockContinue
    {
        return BlockContinue::none();
    }
}
