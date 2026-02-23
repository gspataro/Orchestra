<?php

namespace Orchestra\Project\Definition;

use Orchestra\Project\DefinitionInterface;
use Orchestra\Project\Exception\InvalidConfigException;

abstract class Definition implements DefinitionInterface
{
    public function validate(array $configs): array
    {
        $schema = $this->schema();
        $validated = [];

        foreach ($schema as $field => $rules) {
            $rules['required'] ??= false;
            $rules['type'] ??= 'mixed';
            $rules['default'] ??= null;

            if ($rules['required'] && !array_key_exists($field, $configs)) {
                throw new InvalidConfigException(
                    "Invalid {$this->namespace()} config. Field {$field} is required."
                );
            }

            $value = $configs[$field] ?? $rules['default'];
            $this->assertType($field, $value, $rules['type']);

            $validated[$field] = $value;
        }

        return $validated;
    }

    private function assertType(string $field, mixed $value, ?string $type): void
    {
        if (!$type) {
            return;
        }

        $valid = match ($type) {
            'string' => is_string($value),
            'int' => is_int($value),
            'bool' => is_bool($value),
            'array' => is_array($value),
            default => true
        };

        if (!$valid) {
            throw new InvalidConfigException(
                "Invalid {$this->namespace()} config. Field {$field} must be {$type}."
            );
        }
    }
}
