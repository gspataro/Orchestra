<?php

namespace Orchestra\Markdown\CommonMark\MediaExtension;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Extension\ExtensionInterface;
use Orchestra\View\ElementCollection;

class MediaExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(Image::class, new ImageRenderer(), 1);
    }
}
