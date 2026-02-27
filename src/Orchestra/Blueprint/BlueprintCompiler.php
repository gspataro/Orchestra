<?php

namespace Orchestra\Blueprint;

use Orchestra\Blueprint\Specification\ContentSpecification;
use Orchestra\Blueprint\Specification\WebsiteSpecification;
use Orchestra\Blueprint\Specification\MediaSpecification;
use Orchestra\Blueprint\Specification\SchemaSpecification;
use Orchestra\Project\Exception\InvalidBlueprintException;

final class BlueprintCompiler
{
    /** @var SpecificationInterface[] */
    private array $schemas = [
        WebsiteSpecification::class,
        ContentSpecification::class,
        SchemaSpecification::class,
        MediaSpecification::class
    ];

    private function normalizeRules(array $rules): array
    {
        $rules['type'] ??= 'mixed';
        $rules['required'] ??= false;
        $rules['default'] ??= null;
        $rules['structure'] ??= [];

        return $rules;
    }

    private function validateRepeater(string $field, array $rules, array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            $result[$key] = $this->validateField(
                $field,
                $rules,
                $value ?? $rules['default']
            );
        }

        return $result;
    }

    private function validateObject(string $field, array $rules, array $data): array
    {
        $subFields = array_slice($rules, 1, null, true);
        $value = [];

        foreach ($subFields as $subField => $subRules) {
            $value[$subField] = $this->validateField(
                $field . '.' . $subField,
                $subRules,
                $data[$subField] ?? $subRules['default'] ?? null
            );
        }

        return $value;
    }

    private function validateField(string $field, array $rules, mixed $value): mixed
    {
        $value ??= $rules['default'] ?? null;

        if ($rules['type'] === 'repeater') {
            return $this->validateRepeater(
                $field,
                $this->normalizeRules($rules['structure']),
                $value
            );
        }

        if ($rules['type'] === 'object') {
            return $this->validateObject($field, $rules, $value);
        }

        $rules = $this->normalizeRules($rules);

        if ($rules['required'] && $value === null) {
            throw new InvalidBlueprintException(
                "Invalid blueprint. Field '{$field}' is required."
            );
        }

        if ($value) {
            $valid = match ($rules['type']) {
                'string' => is_string($value),
                'bool' => is_bool($value),
                'int' => is_int($value),
                'array' => is_array($value),
                default => true
            };

            if (!$valid) {
                throw new InvalidBlueprintException(
                    "Invalid blueprint. Field '{$field}' must be of type '{$rules['type']}'"
                );
            }
        }

        return $value;
    }

    private function validator(SpecificationInterface $schema, array $data): NamespaceInterface
    {
        $validated = [];

        foreach ($schema->definition() as $field => $rules) {
            $value = $this->validateField(
                $field === '*' ? $schema->namespace() : $schema->namespace() . '.' . $field,
                $rules,
                $rules['type'] === 'repeater' || $field === '*' ? $data : ($data[$field] ?? null)
            );

            if ($field === '*') {
                $validated = $value;
            } else {
                $validated[$field] = $value;
            }
        }

        return $schema->createNamespace($validated);
    }

    public function compile(Blueprint $blueprint): NamespaceCollection
    {
        $namespaceCollection = new NamespaceCollection();

        foreach ($this->schemas as $schema) {
            $schema = new $schema();
            $namespaceCollection->add(
                $schema->namespace(),
                $this->validator($schema, $blueprint->get($schema->namespace()) ?? [])
            );
        }

        return $namespaceCollection;
    }
}
