<?php

namespace Orchestra\View\Twig;

use Orchestra\Media\MediaResolver;
use Orchestra\Pipeline\BuildContext;
use Orchestra\View\ElementCollection;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

final class TwigMedia extends AbstractExtension
{
    public function __construct(
        private readonly MediaResolver $resolver,
        private readonly ElementCollection $elements
    ) {
    }

    public function media(string $relativePath, ?string $variant = null): string
    {
        $url = getenv('WEBSITE_URL') ?: '';
        $relativePath = $this->resolver->resolve($relativePath, $variant);

        return $url . '/media' . $relativePath;
    }

    public function image(
        string $relativePath,
        ?string $variant = null,
        ?string $altText = null,
        ?string $title = null
    ): string {
        return $this->elements->get('image')->render([
            'relativePath' => $relativePath,
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
