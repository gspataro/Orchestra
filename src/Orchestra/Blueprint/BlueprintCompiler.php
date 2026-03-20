<?php

namespace Orchestra\Blueprint;

use Orchestra\Blueprint\Specification\ContentSpecification;
use Orchestra\Blueprint\Specification\WebsiteSpecification;
use Orchestra\Blueprint\Specification\MediaSpecification;
use Orchestra\Blueprint\Specification\SchemaSpecification;
use Orchestra\Blueprint\Exception\InvalidBlueprintException;
use Orchestra\Blueprint\Specification\OrchestraSpecification;

final class BlueprintCompiler
{
    /** @var array<class-string<SpecificationInterface>> */
    private array $schemas = [
        WebsiteSpecification::class,
        ContentSpecification::class,
        SchemaSpecification::class,
        MediaSpecification::class,
        OrchestraSpecification::class
    ];

    /**
     * @param array<string,string|int|bool|array<string|int,mixed>> $rules
     * @return array<string,string|int|bool|array<string|int,mixed>>
     */
    private function normalizeRules(array $rules): array
    {
        $rules['type'] ??= 'mixed';
        $rules['required'] ??= false;
        $rules['default'] ??= null;
        $rules['structure'] ??= [];

        return $rules;
    }

    /**
     * @param string $field
     * @param array<string,array<string|int,mixed>> $structure
     * @param array<string|int,mixed> $data
     * @return array<string|int,mixed>
     */
    private function validateRepeater(string $field, array $structure, array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            foreach ($structure as $subField => $rules) {
                $result[$key][$subField] = $this->validateField(
                    $field . '.' . $key . '.' . $subField,
                    $this->normalizeRules($rules),
                    $value[$subField] ?? null
                );
            }
        }

        return $result;
    }

    /**
     * @param string $field
     * @param array<string,string|int|bool|array<string|int,mixed>> $rules
     * @param array<string|int,mixed> $data
     * @return array<string|int,mixed>
     */
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

    /**
     * @param string $field
     * @param array<string,string|int|bool|array<string|int,mixed>> $rules
     * @param mixed $value
     * @return mixed
     */
    private function validateField(string $field, array $rules, mixed $value): mixed
    {
        $value ??= $rules['default'] ?? null;

        if ($rules['type'] === 'repeater') {
            return $this->validateRepeater(
                $field,
                $rules['structure'],
                $value ?? []
            );
        }

        if ($rules['type'] === 'object') {
            return $this->validateObject($field, $rules, $value ?? []);
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

    /**
     * @param SpecificationInterface $schema
     * @param array<string|int,mixed> $data
     * @return NamespaceInterface
     */
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
