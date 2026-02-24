<?php

namespace Orchestra\Markdown\CommonMark\ElementsExtension;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use Orchestra\View\ElementCollection;

final class ElementRenderer implements NodeRendererInterface
{
    public function __construct(
        private readonly ElementCollection $elements
    ) {
    }

    /**
     * @param ElementBlock $node
     * @param ChildNodeRendererInterface $childRenderer
     * @return string
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        return $this->elements->get($node->getName())->render($node->getProps());
    }
}
