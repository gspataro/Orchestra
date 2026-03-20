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
                'type' => 'repeater',
                'structure' => [
                    'files' => ['type' => 'string', 'required' => true],
                    'reader' => ['type' => 'string', 'required' => true],
                    'relationships' => [
                        'type' => 'repeater',
                        'structure' => [
                            'with' => ['type' => 'string', 'required' => true],
                            'field' => ['type' => 'string', 'required' => true],
                            'operator' => ['type' => 'string', 'default' => '='],
                            'value' => ['type' => 'string', 'required' => true]
                        ],
                        'default' => []
                    ]
                ],
                'default' => []
            ]
        ];
    }

    public function createNamespace(array $data): ContentNamespace
    {
        return new ContentNamespace($data);
    }
}
