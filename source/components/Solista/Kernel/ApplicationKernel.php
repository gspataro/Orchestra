<?php

namespace GSpataro\Solista\Kernel;

use GSpataro\DependencyInjection\Container;
use GSpataro\Solista\Component;
use GSpataro\Solista\Exception\InvalidComponentException;
use GSpataro\Solista\Kernel;
use GSpataro\Application\Component as AppComponent;

final class ApplicationKernel extends Kernel
{
    private Container $container;

    /** @var Component[] */
    private array $components = [
        AppComponent\ExceptionHandlerComponent::class,
        AppComponent\DotenvComponent::class,
        AppComponent\LocalizationComponent::class,
        AppComponent\ProjectComponent::class,
        AppComponent\HighlightComponent::class,
        AppComponent\AssetsComponent::class,
        AppComponent\TwigComponent::class,
        AppComponent\MarkdownComponent::class,
        AppComponent\LibraryComponent::class,
        AppComponent\FinderComponent::class,
        AppComponent\PagesComponent::class,
        AppComponent\ContractorComponent::class,
        AppComponent\CLIComponent::class
    ];

    private function loadComponents(): void
    {
        foreach ($this->components as &$component) {
            if (get_parent_class($component) !== Component::class) {
                throw new InvalidComponentException(
                    "Component '{$component}' must extend the Component::class"
                );
            }

            if (!is_object($component)) {
                $component = new $component();
            }

            $component->register($this->container);
        }
    }

    private function bootComponents(): void
    {
        foreach ($this->components as $component) {
            $component->boot($this->container);
        }
    }

    public function boot(): void
    {
        $this->container = new Container();

        $this->loadComponents();
        $this->bootComponents();
    }
}
