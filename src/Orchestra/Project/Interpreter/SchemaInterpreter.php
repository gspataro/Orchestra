<?php

namespace Orchestra\Project\Interpreter;

use Orchestra\Blueprint\NamespaceInterface;
use Orchestra\Project\CompilerContext;
use Orchestra\Project\Definition\Query\QueryDefinition;
use Orchestra\Project\Definition\Schema\SchemaDefinition;
use Orchestra\Project\InterpreterInterface;

final class SchemaInterpreter implements InterpreterInterface
{
    public function namespace(): string
    {
        return 'schemas';
    }

    /**
     * @param array<string|array<string,mixed>> $queries
     * @return QueryDefinition[]
     */
    private function buildQueries(array $queries): array
    {
        $contentQueries = [];

        foreach ($queries as $query) {
            if (!is_array($query)) {
                $contentQueries[] = new QueryDefinition($query);
                continue;
            }

            $contentQueries[] = new QueryDefinition(
                $query['group'],
                $query['where'] ?? [],
                $query['skip'] ?? 0,
                $query['limit'] ?? null,
                $query['order']['by'] ?? null,
                match ($query['order']['direction'] ?? 'asc') {
                    'asc' => SORT_ASC,
                    'desc' => SORT_DESC,
                    default => SORT_ASC
                }
            );
        }

        return $contentQueries;
    }

    private function sanitizeSlug(string $slug): string
    {
        if (!str_starts_with($slug, '/')) {
            $slug = '/' . $slug;
        }

        return $slug;
    }

    public function compile(NamespaceInterface $schema, CompilerContext $context): void
    {
        $schemas = $schema->all();

        if (empty($schemas)) {
            return;
        }

        foreach ($schemas as $tag => $schema) {
            $schema = new SchemaDefinition(
                $tag,
                $this->buildQueries($schema['contents']),
                $schema['template'],
                $schema['generate'],
                $schema['source'],
                $schema['builder'],
                $this->sanitizeSlug($schema['slug']),
                $schema['options']
            );

            $context->schemas->add($schema);
        }
    }
}
