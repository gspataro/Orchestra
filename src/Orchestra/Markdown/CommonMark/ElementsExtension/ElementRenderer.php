<?php

namespace Orchestra\Markdown\CommonMark\ElementsExtension;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use Orchestra\View\ElementCollection;

final class ElementRenderer implements NodeRendererInterface
{
    /**
     * @param ElementBlock $node
     * @param ChildNodeRendererInterface $childRenderer
     * @return string
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        $attributes = array_merge($node->getProps(), [
            'name' => $node->getName()
        ]);

        return new HtmlElement(
            tagName: 'orchestra-element',
            attributes: $attributes,
            selfClosing: true
        );
    }
}
