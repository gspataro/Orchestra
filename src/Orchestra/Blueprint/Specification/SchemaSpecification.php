<?php

namespace Orchestra\Blueprint\Specification;

use Orchestra\Blueprint\Namespace\SchemaNamespace;
use Orchestra\Blueprint\SpecificationInterface;

final class SchemaSpecification implements SpecificationInterface
{
    public function namespace(): string
    {
        return 'schemas';
    }

    public function definition(): array
    {
        return [
            '*' => [
                'type' => 'repeater',
                'required' => true,
                'structure' => [
                    'contents' => ['type' => 'array', 'default' => []],
                    'template' => ['type' => 'string', 'required' => true],
                    'slug' => ['type' => 'string', 'required' => true],
                    'generate' => ['type' => 'string', 'default' => 'once'],
                    'builder' => ['type' => 'string', 'default' => 'twig'],
                    'source' => ['type' => 'string', 'default' => ''],
                    'options' => ['type' => 'array', 'default' => []]
                ]
            ]
        ];
    }

    public function createNamespace(array $data): SchemaNamespace
    {
        return new SchemaNamespace($data);
    }
}
