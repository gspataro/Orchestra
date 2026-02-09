<?php

namespace GSpataro\Solista\Kernel;

use GSpataro\Assets\AssetsComponent;
use GSpataro\Contractor\ContractorComponent;
use GSpataro\DependencyInjection\Container;
use GSpataro\Finder\FinderComponent;
use GSpataro\Library\LibraryComponent;
use GSpataro\Localization\LocalizationComponent;
use GSpataro\Pages\PagesComponent;
use GSpataro\Project\ProjectComponent;
use GSpataro\Solista\Component;
use GSpataro\Solista\Component\CLIComponent;
use GSpataro\Solista\Component\DotenvComponent;
use GSpataro\Solista\Component\ExceptionHandlerComponent;
use GSpataro\Solista\Component\HighlightComponent;
use GSpataro\Solista\Component\MarkdownComponent;
use GSpataro\Solista\Exception\InvalidComponentException;
use GSpataro\Solista\Kernel;
use GSpataro\View\ViewComponent;

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
