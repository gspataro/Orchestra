<?php

namespace Orchestra\View\Elements\Image;

use Orchestra\View\ViewElement;

final class ImageElement extends ViewElement
{
    protected string $name = 'image';

    protected function data(array $data = []): array
    {
        $relativePath = $data['relativePath'] ?? '';
        $variant = $data['variant'] ?? null;

        $image = $this->media->request($relativePath, $variant);
        $attributes = [];

        $attributes['src'] = "{$this->url->to('media' . $image->getTransformation($variant)->relativePath)}";
        $attributes['srcset'] = [];

        foreach ($image->getTransformations() as $transformation) {
            $width = $transformation->variant->option('width');

            if ($width) {
                $attributes['srcset'][] = "{$this->url->to('media' . $transformation->relativePath)} {$width}w";
            }
        }

        $width = $image->getTransformation($variant)->variant->option('width');
        $attributes['sizes'] = "(max-width: {$width}px) 100vw, {$width}px";
        $attributes['altText'] = $data['altText'] ?? '';
        $attributes['title'] = $data['title'] ?? '';

        return $attributes;
    }
}
