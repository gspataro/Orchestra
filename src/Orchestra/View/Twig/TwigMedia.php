<?php

namespace Orchestra\View\Twig;

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
        $image = $this->resolver->resolveImage($relativePath, $variant);

        $srcset = null;

        if ($image['srcset']) {
            $srcset = ' srcset="';
            $comma = '';

            foreach ($image['srcset'] as $size => $src) {
                $srcset .= $comma . "{$url}/media{$src} {$size}w";
                $comma = ', ';
            }

            $srcset .= '"';
        }

        $sizes = null;

        if ($image['sizes']) {
            $sizes = " sizes=\"(max-width: {$image['sizes']}px) 100vw, {$image['sizes']}px\"";
        }

        return sprintf(
            '<img src="%s"%s%s>',
            $url . '/media' . $image['src'],
            $srcset,
            $sizes
        );
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
