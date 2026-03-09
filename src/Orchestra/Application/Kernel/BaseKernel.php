<?php

namespace Orchestra\Application\Kernel;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;
use Orchestra\Application\Exception\InvalidComponentException;
use Orchestra\Application\KernelInterface;

abstract class BaseKernel implements KernelInterface
{
    private Container $container;

    /** @var array<class-string<Component>|Component> */
    protected array $components;

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
