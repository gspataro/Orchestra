<?php

namespace Orchestra\Project\Interpreter;

use Orchestra\Blueprint\NamespaceInterface;
use Orchestra\Project\CompilerContext;
use Orchestra\Project\Definition\MediaVariant\MediaVariantDefinition;
use Orchestra\Project\InterpreterInterface;

final class MediaInterpreter implements InterpreterInterface
{
    public function namespace(): string
    {
        return 'media';
    }

    private function readMediaImages(array $images, CompilerContext $context): void
    {
        if (empty($images['sizes'])) {
            return;
        }

        $format = $images['optimize']['strategy'];

        foreach ($images['sizes'] as $size => $options) {
            $mediaVariant = new MediaVariantDefinition(
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

        $context->configs->set('media.image.responsive', $images['responsive']);
    }

    public function compile(NamespaceInterface $media, CompilerContext $context): void
    {
        $this->readMediaImages($media->get('images'), $context);
    }
}
