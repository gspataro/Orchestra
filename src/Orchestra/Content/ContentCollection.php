<?php

namespace Orchestra\Content;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Traversable;
use Countable;
use OutOfBoundsException;

final class ContentCollection implements IteratorAggregate, Countable, ArrayAccess
{
    /**
     * @param Content[] $contents
     */
    public function __construct(
        private array $contents = []
    ) {
    }

    public function add(Content $content): void
    {
        $this->contents[] = $content;
    }

    /**
     * @return Content[]
     */

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->contents);
    }

    /**
     * @return Content[]
     */
    public function toArray(): array
    {
        return $this->contents;
    }

    public function count(): int
    {
        return count($this->contents);
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->contents);
    }

    public function offsetGet(mixed $offset): mixed
    {
        if (!$this->offsetExists($offset)) {
            throw new OutOfBoundsException("Offset '{$offset}' does not exist in ContentCollection.");
        }

        return $this->contents[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        return;
    }

    public function offsetUnset(mixed $offset): void
    {
        return;
    }

    public function query(): ContentQuery
    {
        return new ContentQuery($this);
    }
}
