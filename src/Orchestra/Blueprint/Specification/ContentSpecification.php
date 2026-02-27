<?php

namespace Orchestra\Blueprint\Specification;

use Orchestra\Blueprint\Namespace\ContentNamespace;
use Orchestra\Blueprint\SpecificationInterface;

final class ContentSpecification implements SpecificationInterface
{
    public function namespace(): string
    {
        return 'contents';
    }

    public function definition(): array
    {
        return [
            '*' => [
                'type' => 'array',
                'default' => []
            ]
        ];
    }

    public function createNamespace(array $data): ContentNamespace
    {
        return new ContentNamespace($data);
    }
}
