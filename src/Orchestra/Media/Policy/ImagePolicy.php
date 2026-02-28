<?php

namespace Orchestra\Media\Policy;

use Orchestra\Media\Media;
use Orchestra\Media\MediaTransformer;
use Orchestra\Media\PolicyInterface;
use Orchestra\Compiler\BuildContext;

final class ImagePolicy implements PolicyInterface
{
    /**
     * @return string[]
     */
    public function supports(): array
    {
        return [
            'image/jpeg',
            'image/png'
        ];
    }

    public function apply(Media $media, MediaTransformer $transformer, BuildContext $context): void
    {
        $responsiveVariants = $context->prototype->configs()->get('media.images.responsive');

        if (empty($responsiveVariants ?? [])) {
            return;
        }

        foreach ($responsiveVariants as $variant) {
            $transformer->transform($media, $variant);
        }
    }
}
