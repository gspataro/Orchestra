<?php

namespace Orchestra\Project\Definition\Source;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

final class SourceCollection implements IteratorAggregate
{
    /**
     * @param Source[] $items
     */
    private array $items = [];

    public function add(Source $source): void
    {
        $this->items[] = $source;
    }

    public function get(string $group): ?Source
    {
        return $this->items[$group] ?? null;
    }

    /**
     * @return Source[]
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
