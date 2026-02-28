<?php

namespace Orchestra\Markdown\CommonMark\ElementsExtension;

use League\CommonMark\Node\Block\AbstractBlock;

final class ElementBlock extends AbstractBlock
{
    /**
     * @param string $name
     * @param array<string,mixed> $props
     */
    public function __construct(
        private string $name,
        private array $props = []
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<string,mixed>
     */
    public function getProps(): array
    {
        return $this->props;
    }
}
