<?php

namespace Orchestra\View\Twig;

use Orchestra\Media\MediaResolver;
use Orchestra\Compiler\UrlGenerator;
use Orchestra\View\ElementCollection;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

final class MediaExtension extends AbstractExtension
{
    public function __construct(
        private readonly MediaResolver $resolver,
        private readonly ElementCollection $elements,
        private readonly UrlGenerator $url
    ) {
    }

    public function media(string $relativePath, ?string $variant = null): string
    {
        $relativePath = $this->resolver->resolve($relativePath, $variant);
        return $this->url->to('/media' . $relativePath);
    }

    public function image(
        string $src,
        ?string $variant = null,
        ?string $altText = null,
        ?string $title = null
    ): string {
        return $this->elements->get('image')->render([
            'src' => $src,
            'variant' => $variant,
            'altText' => $altText,
            'title' => $title
        ]);
    }

    public function getFunctions()
    {
        $functions = [];

        $functions[] = new TwigFunction('media', [$this, 'media']);
        $functions[] = new TwigFunction('image', [$this, 'image'], [
            'is_safe' => ['html']
        ]);

        return $functions;
    }
}
