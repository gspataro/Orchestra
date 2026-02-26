<?php

namespace Orchestra\Content;

use Orchestra\Project\Definition\Query\QueryDefinition;

final class ContentRepository
{
    /** @var Content[] */
    private array $byId = [];

    /** @var Content[] */
    private array $byGroup = [];

    public function add(Content $content): void
    {
        $this->byId[$content->id][] = $content;
        $this->byGroup[$content->group][] = $content;
    }

    public function group(string $group): ContentCollection
    {
        /** @var Content[] */
        $contents = $this->byGroup[$group] ?? [];

        return new ContentCollection($contents);
    }

    public function all(): ContentCollection
    {
        return new ContentCollection($this->byId);
    }

    public function query(QueryDefinition $definition): ContentQuery
    {
        /** @var ContentCollection */
        $contents = $this->group($definition->group);
        return $contents->query()->fromDefinition($definition);
    }
}
