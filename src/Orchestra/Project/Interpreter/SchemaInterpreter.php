<?php

namespace Orchestra\Project\Interpreter;

use Orchestra\Blueprint\Blueprint;
use Orchestra\Project\CompilerContext;
use Orchestra\Project\Definition\Query\QueryDefinition;
use Orchestra\Project\Definition\Schema\Schema;
use Orchestra\Project\Exception\InvalidBlueprintException;
use Orchestra\Project\Exception\InvalidSchemaException;
use Orchestra\Project\InterpreterInterface;

final class SchemaInterpreter implements InterpreterInterface
{
    private function validateSchema(string $tag, array $schema): void
    {
        if (!isset($schema['template'])) {
            throw new InvalidBlueprintException(
                "You must provide a template for schema '{$tag}'."
            );
        }

        if (!isset($schema['generate'])) {
            throw new InvalidSchemaException(
                "You must provide a generator for schema '{$tag}'."
            );
        }

        if (!isset($schema['slug'])) {
            throw new InvalidBlueprintException(
                "You must provide a slug for schema '{$tag}'."
            );
        }
    }

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

    public function compile(Blueprint $blueprint, CompilerContext $context): void
    {
        $schemas = $blueprint->get('schemas') ?? [];

        if (empty($schemas)) {
            return;
        }

        foreach ($schemas as $tag => $schema) {
            $this->validateSchema($tag, $schema);

            $schema = new Schema(
                $tag,
                $this->buildQueries($schema['contents'] ?? []),
                $schema['template'],
                $schema['generate'],
                $schema['source'] ?? '',
                $schema['builder'] ?? 'twig',
                $this->sanitizeSlug($schema['slug']),
                $schema['options'] ?? []
            );

            $context->schemas->add($schema);
        }
    }
}
