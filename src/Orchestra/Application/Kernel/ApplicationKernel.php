<?php

namespace Orchestra\Application\Kernel;

use GSpataro\DependencyInjection\Container;
use Orchestra\Assets\AssetsComponent;
use Orchestra\Console\ConsoleComponent;
use Orchestra\Contractor\ContractorComponent;
use Orchestra\Finder\FinderComponent;
use Orchestra\Content\ContentComponent;
use Orchestra\Localization\LocalizationComponent;
use Orchestra\Pages\PagesComponent;
use Orchestra\Project\ProjectComponent;
use Orchestra\Application\Component;
use Orchestra\Application\Exception\InvalidComponentException;
use Orchestra\Application\Kernel;
use Orchestra\Infrastructure\DotenvComponent;
use Orchestra\Infrastructure\ExceptionHandlerComponent;
use Orchestra\Infrastructure\HighlightComponent;
use Orchestra\Infrastructure\MarkdownComponent;
use Orchestra\Pipeline\PipelineComponent;
use Orchestra\View\ViewComponent;

final class ApplicationKernel extends Kernel
{
    private Container $container;

    /** @var Component[] */
    private array $components = [
        ExceptionHandlerComponent::class,
        DotenvComponent::class,
        PipelineComponent::class,
        //LocalizationComponent::class,
        ProjectComponent::class,
        HighlightComponent::class,
        AssetsComponent::class,
        ViewComponent::class,
        MarkdownComponent::class,
        ContentComponent::class,
        FinderComponent::class,
        PagesComponent::class,
        ContractorComponent::class,
        ConsoleComponent::class
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
