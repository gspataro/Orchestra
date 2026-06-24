<?php

namespace Orchestra\Publisher;

use Orchestra\Page\Page;

final class Publisher
{
    private ?OutputStrategyInterface $outputStrategy = null;

    public function __construct(
        private readonly Builder $builder,
        private readonly AdapterInterface $adapter,
        private readonly OutputRegistry $registry
    ) {
    }

    public function setOutputStrategy(OutputStrategyInterface $strategy): void
    {
        $this->outputStrategy = $strategy;
    }

    public function publish(Page $page): void
    {
        $path = $this->outputStrategy ? $this->outputStrategy->apply($page->permalink) : $page->permalink;
        $content = $this->builder->build($page);

        $this->registry->add($path);

        $this->adapter->handle($path, $content);
    }
}
