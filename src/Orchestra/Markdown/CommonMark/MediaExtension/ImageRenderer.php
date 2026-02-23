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
use Orchestra\Media\MediaResolver;
use Stringable;

final class ImageRenderer implements NodeRendererInterface, XmlNodeRendererInterface
{
    public function __construct(
        private readonly MediaResolver $resolver
    ) {
    }

    /**
     * @param Image $node
     * @param ChildNodeRendererInterface $childRenderer
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): Stringable
    {
        $attributes = $node->data->get('attributes', []);

        $urlParts = parse_url($node->getUrl());
        parse_str($urlParts['query'], $query);
        $relativePath = $urlParts['path'];
        $variant = $query['variant'] ?? null;

        $url = getenv('WEBSITE_URL') ?: '';
        $image = $this->resolver->request($relativePath, $variant);
        $attributes = [];

        $attributes['src'] = "{$url}/media{$image->getTransformation($variant)->relativePath}";
        $attributes['srcset'] = [];

        foreach ($image->getTransformations() as $transformation) {
            $width = $transformation->variant->option('width');

            if ($width) {
                $attributes['srcset'][] = "{$url}/media{$transformation->relativePath} {$width}w";
            }
        }

        $width = $image->getTransformation($variant)->variant->option('width');
        $attributes['sizes'] = "(max-width: {$width}px) 100vw, {$width}px";

        return new HtmlElement('img', $attributes, selfClosing: true);
    }

    public function getXmlTagName(Node $node): string
    {
        return 'image';
    }

    /**
     * @param Image $node
     * @return array
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
