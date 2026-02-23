<?php

namespace Orchestra\View\Twig;

use Dom\HTMLElement;
use Orchestra\Media\MediaResolver;
use Orchestra\Pipeline\BuildContext;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

final class TwigMedia extends AbstractExtension
{
    public function __construct(
        private readonly MediaResolver $resolver
    ) {
    }

    public function media(string $relativePath, ?string $variant = null): string
    {
        $url = getenv('WEBSITE_URL') ?: '';
        $relativePath = $this->resolver->resolve($relativePath, $variant);

        return $url . '/media' . $relativePath;
    }

    public function image(string $relativePath, ?string $variant = null): string
    {
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
        $srcset = implode(', ', $attributes['srcset']);

        return "<img src=\"{$attributes['src']}\" srcset=\"{$srcset}\" sizes=\"{$attributes['sizes']}\">";
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
