<?php

namespace Orchestra\Project\Definition\Relationship;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int,RelationshipDefinition>
 */
final class RelationshipDefinitionCollection implements IteratorAggregate
{
    /** @var array<string,RelationshipDefinition[]> $items */
    private array $items = [];

    public function add(RelationshipDefinition $relationship): void
    {
        $this->items[$relationship->group][] = $relationship;
    }

    /**
     * @param string $group
     * @return RelationshipDefinition[]
     */
    public function group(string $group): array
    {
        return $this->items[$group] ?? [];
    }

    /**
     * @return Traversable<RelationshipDefinition>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
