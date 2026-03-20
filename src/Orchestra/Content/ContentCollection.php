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
    /** @var Content[] */
    private array $byId = [];

    /** @var Content[] */
    private array $byTag = [];

    /**
     * @param Content[] $contents
     */
    public function __construct(array $contents = [])
    {
        foreach ($contents as $content) {
            $this->add($content);
        }
    }

    public function add(Content $content): void
    {
        $this->byId[$content->id] = $content;
        $this->byTag[$content->tag] = $content;
    }

    public function replace(Content $content): void
    {
        $this->byId[$content->id] = $content;
        $this->byTag[$content->tag] = $content;
    }

    /**
     * @return Content[]
     */
    public function allByTag(): array
    {
        return $this->byTag;
    }

    /**
     * @return Traversable<string,Content>
     */

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->byId);
    }

    /**
     * @return array<string,Content>
     */
    public function toArray(): array
    {
        return $this->byId;
    }

    public function count(): int
    {
        return count($this->byId);
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->byId);
    }

    public function offsetGet(mixed $offset): Content
    {
        if (!$this->offsetExists($offset)) {
            throw new OutOfBoundsException("Offset '{$offset}' does not exist in ContentCollection.");
        }

        return $this->byId[$offset];
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
