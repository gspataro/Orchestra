<?php

namespace Orchestra\Project\Definition\Source;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

final class SourceDefinitionCollection implements IteratorAggregate
{
    /**
     * @param SourceDefinition[] $items
     */
    private array $items = [];

    public function add(SourceDefinition $source): void
    {
        $this->items[] = $source;
    }

    public function get(string $group): ?SourceDefinition
    {
        return $this->items[$group] ?? null;
    }

    /**
     * @return SourceDefinition[]
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
