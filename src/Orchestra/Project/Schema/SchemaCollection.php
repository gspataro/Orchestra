<?php

namespace Orchestra\Project\Schema;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

final class SchemaCollection implements IteratorAggregate
{
    /**
     * @param Schema[] $items
     */
    public function __construct(
        private array $items
    ) {
    }

    public function get(string $group): ?Schema
    {
        return $this->items[$group] ?? null;
    }

    /**
     * @return Schema[]
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
