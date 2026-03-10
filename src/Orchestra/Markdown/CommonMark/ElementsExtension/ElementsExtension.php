<?php

namespace Orchestra\Markdown\CommonMark\ElementsExtension;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;
use Orchestra\Markdown\CommonMark\ElementsExtension\ElementBlockStartParser;
use Orchestra\Markdown\CommonMark\ElementsExtension\ElementRenderer;
use Orchestra\View\ElementCollection;

class ElementsExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment
            ->addBlockStartParser(new ElementBlockStartParser())
            ->addRenderer(ElementBlock::class, new ElementRenderer());
    }
}
