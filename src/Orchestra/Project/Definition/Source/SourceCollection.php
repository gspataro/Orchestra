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
    public function __construct(
        private array $items
    ) {
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
