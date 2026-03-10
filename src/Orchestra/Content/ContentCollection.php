<?php

namespace Orchestra\Content;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Traversable;
use Countable;
use Orchestra\Content\Exception\ContentCollectionException;
use OutOfBoundsException;

/**
 * @implements ArrayAccess<string,Content>
 * @implements IteratorAggregate<string,Content>
 */
final class ContentCollection implements IteratorAggregate, Countable, ArrayAccess
{
    /**
     * @param array<string,Content> $contents
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
     * @return Traversable<string,Content>
     */

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->contents);
    }

    /**
     * @return array<string,Content>
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

    public function offsetGet(mixed $offset): Content
    {
        if (!$this->offsetExists($offset)) {
            throw new OutOfBoundsException("Offset '{$offset}' does not exist in ContentCollection.");
        }

        return $this->contents[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new ContentCollectionException("Cannot set offset '{$offset}' on ContentCollection.");
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new ContentCollectionException("Cannot unset offset '{$offset}' on ContentCollection.");
    }

    public function query(): ContentQuery
    {
        return new ContentQuery($this);
    }
}
