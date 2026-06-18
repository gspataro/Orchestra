<?php

namespace Orchestra\Publisher;

final class OutputStrategyCollection
{
    /** @var array<string,OutputStrategyInterface> */
    private array $strategies = [];

    public function add(string $name, OutputStrategyInterface $strategy): void
    {
        $this->strategies[$name] = $strategy;
    }

    public function get(string $name): OutputStrategyInterface
    {
        return $this->strategies[$name];
    }
}
