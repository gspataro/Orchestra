<?php

namespace Orchestra\Markdown\CommonMark\MediaExtension;

use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Node\Inline\Newline;
use League\CommonMark\Node\Node;
use League\CommonMark\Node\NodeIterator;
use League\CommonMark\Node\StringContainerInterface;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Xml\XmlNodeRendererInterface;
use Orchestra\View\ElementCollection;

final class ImageRenderer implements NodeRendererInterface, XmlNodeRendererInterface
{
    /**
     * @param Image $node
     * @param ChildNodeRendererInterface $childRenderer
     * @return string
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        $urlParts = parse_url($node->getUrl());
        $relativePath = $urlParts['path'] ?? '';
        $variant = $urlParts['query']['variant'] ?? null;

        return new HtmlElement(
            tagName: 'orchestra-element',
            attributes: [
                'name' => 'image',
                'relativePath' => $relativePath,
                'variant' => $variant ?? '',
                'altText' => $this->getAltText($node),
                'title' => $node->getTitle()
            ],
            selfClosing: true
        );
    }

    public function getXmlTagName(Node $node): string
    {
        return 'image';
    }

    /**
     * @param Image $node
     * @return array<string,string>
     */
    public function getXmlAttributes(Node $node): array
    {
        Image::assertInstanceOf($node);

        return [
            'destination' => $node->getUrl(),
            'title' => $node->getTitle() ?? '',
        ];
    }

    private function getAltText(Image $node): string
    {
        $altText = '';

        foreach ((new NodeIterator($node)) as $n) {
            if ($n instanceof StringContainerInterface) {
                $altText .= $n->getLiteral();
            } elseif ($n instanceof Newline) {
                $altText .= "\n";
            }
        }

        return $altText;
    }
}
