<?php

namespace GSpataro\Solista;

use GSpataro\Application\Component;
use GSpataro\DependencyInjection\Container;

class Solista
{
    private Container $container;

    public static function run(): self
    {
        $app = new self();

        $app->loadContainer();
        $app->boot();

        return $app;
    }

    private function loadContainer(): void
    {
        $this->container = new Container();

        $this->container->loadComponents([
            Component\ExceptionHandlerComponent::class,
            Component\DotenvComponent::class,
            Component\LocalizationComponent::class,
            Component\ProjectComponent::class,
            Component\HighlightComponent::class,
            Component\AssetsComponent::class,
            Component\TwigComponent::class,
            Component\MarkdownComponent::class,
            Component\LibraryComponent::class,
            Component\FinderComponent::class,
            Component\PagesComponent::class,
            Component\ContractorComponent::class,
            Component\CLIComponent::class
        ]);
    }

    private function boot(): void
    {
        $this->container->boot();
    }
}
