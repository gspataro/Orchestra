<?php

namespace Orchestra\Project\Definition\Source;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int,SourceDefinition>
 */
final class SourceDefinitionCollection implements IteratorAggregate
{
    /** @var SourceDefinition[] $items */
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
     * @return Traversable<SourceDefinition>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
