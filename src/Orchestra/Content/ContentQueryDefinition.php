<?php

namespace Orchestra\Content;

final readonly class ContentQueryDefinition
{
    public function __construct(
        public string $group,
        public array $wheres = [],
        public int $skip = 0,
        public ?int $limit = null,
        public ?string $orderField = null,
        public int $sortDirection = SORT_ASC
    ) {
    }
}
