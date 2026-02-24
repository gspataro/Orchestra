<?php

namespace Orchestra\Project\Definition;

use Orchestra\Project\Config\WebsiteConfig;
use Orchestra\Project\ConfigInterface;

class WebsiteDefinition extends Definition
{
    public function namespace(): string
    {
        return 'website';
    }

    public function schema(): array
    {
        return [
            'name' => [
                'type' => 'string',
                'default' => 'My Solista Website'
            ],
            'description' => [
                'type' => 'string',
                'default' => 'PHP static website builder'
            ],
            'theme' => [
                'type' => 'string',
                'default' => 'pianoforte',
                'required' => true
            ],
            'friendly_urls' => [
                'type' => 'bool',
                'default' => true
            ]
        ];
    }
}
