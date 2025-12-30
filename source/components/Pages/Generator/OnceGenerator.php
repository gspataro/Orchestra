<?php

namespace GSpataro\Pages\Generator;

final class OnceGenerator extends BaseGenerator
{
    /**
     * Generate pages based on schema
     *
     * @param array $schema
     * @return void
     */

    public function generate(array $schema): void
    {
        $this->createPage(
            $schema['tag'],
            $this->sitemap->add($schema['tag'], $schema['slug']),
            $schema['template'],
            $schema['builder'],
            $schema['contents']
        );
    }
}
