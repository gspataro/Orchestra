<?php

namespace Orchestra\Content;

use ArrayIterator;
use IteratorAggregate;
use Traversable;
use Countable;

final class ContentCollection implements IteratorAggregate, Countable
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

    public function query(): ContentQuery
    {
        return new ContentQuery($this);
    }
}
