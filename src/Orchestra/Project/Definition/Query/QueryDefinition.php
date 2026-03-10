<?php

namespace Orchestra\Project\Definition\Query;

final readonly class QueryDefinition
{
    /**
     * @param string $group
     * @param array<array<string>> $wheres
     * @param integer $skip
     * @param integer|null $limit
     * @param string|null $orderField
     * @param int $sortDirection
     * @param array<string,mixed> $relationships
     */
    public function __construct(
        public string $group,
        public array $wheres = [],
        public int $skip = 0,
        public ?int $limit = null,
        public ?string $orderField = null,
        public int $sortDirection = SORT_ASC,
        public array $relationships = []
    ) {
    }
}
