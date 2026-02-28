<?php

namespace Orchestra\Project\Definition\Schema;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

final class SchemaDefinitionCollection implements IteratorAggregate
{
    /**
     * @param SchemaDefinition[] $items
     */
    private array $items = [];

    public function add(SchemaDefinition $schema): void
    {
        $this->items[] = $schema;
    }

    public function get(string $group): ?SchemaDefinition
    {
        return $this->items[$group] ?? null;
    }

    /**
     * @return Traversable<SchemaDefinition>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
