<?php

namespace Orchestra\Markdown\CommonMark\MediaExtension;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Extension\ExtensionInterface;
use Orchestra\Media\MediaResolver;

class MediaExtension implements ExtensionInterface
{
    public function __construct(
        private readonly MediaResolver $resolver
    ) {
    }

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(Image::class, new ImageRenderer($this->resolver), 1);
    }
}
