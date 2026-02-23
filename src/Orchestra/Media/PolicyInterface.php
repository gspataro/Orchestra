<?php

namespace Orchestra\Media;

use Orchestra\Pipeline\BuildContext;

interface PolicyInterface
{
    /**
     * @return string[]
     */
    public function supports(): array;

    public function apply(Media $media, MediaTransformer $transformer, BuildContext $context): void;
}
