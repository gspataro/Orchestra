<?php

namespace Orchestra\Application\Kernel;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;
use Orchestra\Application\Exception\InvalidComponentException;
use Orchestra\Application\KernelInterface;

abstract class BaseKernel implements KernelInterface
{
    private Container $container;

    /** @var class-string<Component>[] */
    protected array $components;

    /** @var Component[] */
    private array $bootedComponents = [];

    private function loadComponents(): void
    {
        foreach ($this->components as $component) {
            if (!is_a($component, Component::class, true)) {
                throw new InvalidComponentException(
                    "Component '{$component}' must extend the Component::class"
                );
            }

            $this->bootedComponents[$component] = new $component();
            $this->bootedComponents[$component]->register($this->container);
        }
    }

    private function bootComponents(): void
    {
        foreach ($this->bootedComponents as $component) {
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
