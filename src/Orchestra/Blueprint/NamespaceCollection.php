<?php

namespace Orchestra\Blueprint;

final class NamespaceCollection
{
    /** @var NamespaceInterface[] */
    private array $items = [];

    public function add(string $id, NamespaceInterface $namespace): void
    {
        $this->items[$id] = $namespace;
    }

    public function get(string $id): ?NamespaceInterface
    {
        return $this->items[$id] ?? null;
    }
}
