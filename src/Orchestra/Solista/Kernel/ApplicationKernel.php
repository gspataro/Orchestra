<?php

namespace Orchestra\Solista\Kernel;

use GSpataro\DependencyInjection\Container;
use Orchestra\Assets\AssetsComponent;
use Orchestra\CLI\CLIComponent;
use Orchestra\Contractor\ContractorComponent;
use Orchestra\Finder\FinderComponent;
use Orchestra\Library\LibraryComponent;
use Orchestra\Localization\LocalizationComponent;
use Orchestra\Pages\PagesComponent;
use Orchestra\Project\ProjectComponent;
use Orchestra\Solista\Component;
use Orchestra\Solista\Component\DotenvComponent;
use Orchestra\Solista\Component\ExceptionHandlerComponent;
use Orchestra\Solista\Component\HighlightComponent;
use Orchestra\Solista\Component\MarkdownComponent;
use Orchestra\Solista\Exception\InvalidComponentException;
use Orchestra\Solista\Kernel;
use Orchestra\View\ViewComponent;

final class ApplicationKernel extends Kernel
{
    private Container $container;

    /** @var Component[] */
    private array $components = [
        ExceptionHandlerComponent::class,
        DotenvComponent::class,
        LocalizationComponent::class,
        ProjectComponent::class,
        HighlightComponent::class,
        AssetsComponent::class,
        ViewComponent::class,
        MarkdownComponent::class,
        LibraryComponent::class,
        FinderComponent::class,
        PagesComponent::class,
        ContractorComponent::class,
        CLIComponent::class
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
