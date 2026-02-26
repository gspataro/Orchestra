<?php

namespace Orchestra\Project\Interpreter;

use Orchestra\Project\Blueprint;
use Orchestra\Project\CompilerContext;
use Orchestra\Project\Definition\MediaVariant\MediaVariant;
use Orchestra\Project\InterpreterInterface;

final class MediaInterpreter implements InterpreterInterface
{
    private function readMediaImages(array $images, CompilerContext $context): void
    {
        if (empty($images) || empty($images['sizes'])) {
            return;
        }

        $format = $images['optimize']['strategy'] ?? null;

        foreach ($images['sizes'] as $size => $options) {
            $mediaVariant = new MediaVariant(
                $size,
                $format ?? null,
                [
                    'width' => $options['width'] ?? null,
                    'height' => $options['height'] ?? null,
                    'quality' => $options['quality'] ?? null,
                    'crop' => $options['crop'] ?? null
                ]
            );

            $context->mediaVariants->add('image', $size, $mediaVariant);
        }

        $context->configs->set('media.image.responsive', $images['responsive'] ?? ['thumbnail, medium, large']);
    }

    public function compile(Blueprint $blueprint, CompilerContext $context): void
    {
        $this->readMediaImages($blueprint->get('media.images') ?? [
            'optimize' => 'webp',
            'sizes' => [
                'thumbnail' => ['width' => 200, 'height' => 200, 'resize' => true],
                'medium' => ['width' => 400, 'height' => 400],
                'large' => ['width' => 1024, 'height' => 1024],
                'original' => []
            ]
        ], $context);
    }
}
