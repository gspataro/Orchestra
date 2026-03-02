<?php

namespace Orchestra\Application\Kernel;

use GSpataro\DependencyInjection\Container;
use Orchestra\Publisher\PublisherComponent;
use Orchestra\Content\ContentComponent;
use Orchestra\Page\PagesComponent;
use Orchestra\Project\ProjectComponent;
use Orchestra\Application\Component;
use Orchestra\Application\Exception\InvalidComponentException;
use Orchestra\Application\Kernel;
use Orchestra\Blueprint\BlueprintComponent;
use Orchestra\Cache\CacheComponent;
use Orchestra\Infrastructure\HighlightComponent;
use Orchestra\Markdown\MarkdownComponent;
use Orchestra\Media\MediaComponent;
use Orchestra\Compiler\CompilerComponent;
use Orchestra\Rehearsal\RehearsalComponent;
use Orchestra\Theme\ThemeComponent;
use Orchestra\View\ViewComponent;

final class RehearsalKernel extends Kernel
{
    private Container $container;

    /** @var array<class-string<Component>|Component> */
    private array $components = [
        CompilerComponent::class,
        BlueprintComponent::class,
        ProjectComponent::class,
        RehearsalComponent::class,
        HighlightComponent::class,
        CacheComponent::class,
        ThemeComponent::class,
        ViewComponent::class,
        MarkdownComponent::class,
        ContentComponent::class,
        PagesComponent::class,
        PublisherComponent::class,
        MediaComponent::class
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

    public function boot(): Container
    {
        $this->container = new Container();

        $this->loadComponents();
        $this->bootComponents();

        return $this->container;
    }
}
