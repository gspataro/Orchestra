<?php

namespace Orchestra\Project\Content;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

final class ContentCollection implements IteratorAggregate
{
    /**
     * @param Content[] $items
     */
    public function __construct(
        private array $items
    ) {
    }

    public function get(string $group): ?Content
    {
        return $this->items[$group] ?? null;
    }

    /**
     * @return Content[]
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
