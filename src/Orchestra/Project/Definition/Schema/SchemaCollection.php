<?php

namespace Orchestra\Project\Definition\Schema;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

final class SchemaCollection implements IteratorAggregate
{
    /**
     * @param Schema[] $items
     */
    private array $items = [];

    public function add(Schema $schema): void
    {
        $this->items[] = $schema;
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
