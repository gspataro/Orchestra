<?php

namespace Orchestra\Blueprint\Specification;

use Orchestra\Blueprint\Namespace\OrchestraNamespace;
use Orchestra\Blueprint\SpecificationInterface;

final class OrchestraSpecification implements SpecificationInterface
{
    public function namespace(): string
    {
        return 'orchestra';
    }

    public function definition(): array
    {
        return [
            'cleanup' => ['type' => 'array', 'default' => []]
        ];
    }

    public function createNamespace(array $data): OrchestraNamespace
    {
        return new OrchestraNamespace($data);
    }
}
