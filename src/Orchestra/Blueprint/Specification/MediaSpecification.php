<?php

namespace Orchestra\Blueprint\Specification;

use Orchestra\Blueprint\Namespace\MediaNamespace;
use Orchestra\Blueprint\NamespaceInterface;
use Orchestra\Blueprint\SpecificationInterface;

final class MediaSpecification implements SpecificationInterface
{
    public function namespace(): string
    {
        return 'media';
    }

    public function definition(): array
    {
        return [
            'images' => [
                'type' => 'object',
                'optimize' => [
                    'type' => 'object',
                    'strategy' => ['type' => 'string', 'default' => 'webp']
                ],
                'sizes' => [
                    'type' => 'repeater',
                    'structure' => [
                        'width' => ['type' => 'int'],
                        'height' => ['type' => 'int'],
                        'crop' => ['type' => 'bool', 'default' => false],
                        'quality' => ['type' => 'int', 'default' => 100]
                    ],
                    'default' => [
                        'thumbnail' => ['width' => 150, 'height' => 150, 'crop' => true, 'quality' => 80],
                        'medium' => ['width' => 500, 'height' => null, 'crop' => false, 'quality' => 80],
                        'large' => ['width' => 1000, 'height' => null, 'crop' => false, 'quality' => 80],
                        'original' => ['width' => null, 'height' => null, 'crop' => false, 'quality' => 100]
                    ]
                ],
                'responsive' => [
                    'type' => 'array',
                    'default' => ['thumbnail', 'medium', 'large']
                ]
            ]
        ];
    }

    public function createNamespace(array $data): NamespaceInterface
    {
        return new MediaNamespace($data);
    }
}
