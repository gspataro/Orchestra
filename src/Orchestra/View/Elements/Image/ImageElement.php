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

        if (is_null($image)) {
            return [];
        }

        $attributes = [];

        $mediaTransformation = $image->getTransformation($variant);

        if (!is_null($mediaTransformation)) {
            $attributes['src'] = "{$this->url->to('media' . $mediaTransformation->relativePath)}";
            $width = $mediaTransformation->variant->option('width');
            $attributes['sizes'] = "(max-width: {$width}px) 100vw, {$width}px";
        } else {
            $attributes['src'] = "{$this->url->to('media' . $image->relativePath)}";
        }

        $attributes['srcset'] = [];

        foreach ($image->getTransformations() as $transformation) {
            $width = $transformation->variant->option('width');

            if ($width) {
                $attributes['srcset'][] = "{$this->url->to('media' . $transformation->relativePath)} {$width}w";
            }
        }

        $attributes['altText'] = $data['altText'] ?? '';
        $attributes['title'] = $data['title'] ?? '';

        return $attributes;
    }
}
