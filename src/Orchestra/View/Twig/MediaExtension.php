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
        array $attributes = []
    ): string {
        return $this->elements->get('image')->render([
            'src' => $src,
            'variant' => $variant,
            'attributes' => $attributes
        ]);
    }

    public function audio(string $src, array $attributes = []): string
    {
        return $this->elements->get('audio')->render([
            'src' => $src,
            'attributes' => $attributes
        ]);
    }

    public function svg(string $src, array $attributes = []): string
    {
        return $this->elements->get('svg')->render([
            'src' => $src,
            'attributes' => $attributes
        ]);
    }

    public function getFunctions()
    {
        $functions = [];

        $functions[] = new TwigFunction('media', [$this, 'media']);
        $functions[] = new TwigFunction('image', [$this, 'image'], [
            'is_safe' => ['html']
        ]);
        $functions[] = new TwigFunction('svg', [$this, 'svg'], [
            'is_safe' => ['html']
        ]);

        return $functions;
    }
}
