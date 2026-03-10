<?php

namespace Orchestra\Blueprint\Specification;

use Orchestra\Blueprint\Namespace\WebsiteNamespace;
use Orchestra\Blueprint\SpecificationInterface;

final class WebsiteSpecification implements SpecificationInterface
{
    public function namespace(): string
    {
        return 'website';
    }

    public function definition(): array
    {
        return [
            'name' => ['type' => 'string', 'default' => 'Solista'],
            'description' => ['type' => 'string', 'default' => 'PHP static website builder'],
            'theme' => ['type' => 'string', 'default' => 'pianoforte'],
            'url' => ['type' => 'string', 'default' => ''],
            'friendly_urls' => ['type' => 'boolean', 'default' => false]
        ];
    }

    public function createNamespace(array $data): WebsiteNamespace
    {
        return new WebsiteNamespace($data);
    }
}
