<?php

namespace Orchestra\Page;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int,Page>
 */
final class PageCollection implements IteratorAggregate
{
    /** @var Page[] */
    private array $pages = [];

    public function add(Page $page): void
    {
        $this->pages[] = $page;
    }

    /**
     * @return Traversable<Page>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->pages);
    }
}
