<?php

namespace Orchestra\Theme\Assets;

final class DriverCollection
{
    /** @var DriverInterface[] */
    private array $drivers = [];

    public function add(string $name, DriverInterface $driver): void
    {
        if (isset($this->drivers[$name])) {
            return;
        }

        $this->drivers[$name] = $driver;
    }

    public function get(string $name): ?DriverInterface
    {
        return $this->drivers[$name] ?? null;
    }
}
