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

    /** @var Page[] */
    private array $byPermalink = [];

    public function add(Page $page): void
    {
        $this->pages[] = $page;
        $this->byPermalink[$page->permalink] = $page;
    }

    public function get(string $permalink): ?Page
    {
        return $this->byPermalink[$permalink] ?? null;
    }

    /**
     * @return Traversable<Page>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->pages);
    }
}
