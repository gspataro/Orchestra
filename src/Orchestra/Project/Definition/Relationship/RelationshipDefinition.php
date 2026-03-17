<?php

namespace Orchestra\Project\Definition\Relationship;

final readonly class RelationshipDefinition
{
    public function __construct(
        public string $group,
        public string $with,
        public string $field,
        public string $operator,
        public string $value
    ) {
    }
}
